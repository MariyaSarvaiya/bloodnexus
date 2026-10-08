<?php

namespace App\Http\Controllers;

use App\Helpers\NotificationHelper;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\Support\BloodCompatibility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BloodRequestController extends Controller
{
    public function sendToDonor(Request $request, Donor $donor)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        abort_unless(Auth::user()->role === 'user', 403, 'Only Blood Need users can send blood requests.');

        $bloodRequest = BloodRequest::where('id', $request->integer('request_id'))
            ->where('user_id', Auth::id())
            ->whereNull('parent_request_id')
            ->firstOrFail();

        if (!in_array($bloodRequest->status, ['pending', 'matched', 'accepted'], true) || $bloodRequest->remainingUnits() <= 0) {
            return back()->with('error', 'This blood request is already completed, cancelled, or fully fulfilled.');
        }

        if (!$donor->isAvailable() || !$donor->canDonate()) {
            return back()->with('error', 'This donor is currently unavailable.');
        }

        if (!BloodCompatibility::canDonateTo($donor->blood_group, $bloodRequest->blood_group)) {
            return back()->with('error', 'This donor is not compatible with the requested blood group.');
        }

        $alreadySent = BloodRequest::where('parent_request_id', $bloodRequest->id)
            ->where('donor_id', $donor->id)
            ->whereIn('status', ['pending', 'accepted'])
            ->exists();

        if ($alreadySent) {
            return back()->with('error', 'This donor has already received this blood request.');
        }

        $reservedUnits = BloodRequest::where('parent_request_id', $bloodRequest->id)
            ->whereIn('status', ['pending', 'accepted'])
            ->sum('units');

        if ((int) $reservedUnits >= $bloodRequest->remainingUnits()) {
            return back()->with('error', 'All remaining blood units are already reserved with other donors.');
        }

        DB::transaction(function () use ($bloodRequest, $donor) {
            BloodRequest::create([
                'user_id' => Auth::id(),
                'donor_id' => $donor->id,
                'parent_request_id' => $bloodRequest->id,
                'patient_name' => $bloodRequest->patient_name,
                'requester_type' => $bloodRequest->requester_type,
                'request_for' => $bloodRequest->request_for,
                'blood_group' => $bloodRequest->blood_group,
                'city' => $bloodRequest->city,
                'area' => $bloodRequest->area,
                'hospital' => $bloodRequest->hospital,
                'contact' => $bloodRequest->contact,
                'contact_phone' => $bloodRequest->contact_phone,
                'units' => 1,
                'units_fulfilled' => 0,
                'urgency' => $bloodRequest->urgency,
                'emergency_mode' => $bloodRequest->emergency_mode,
                'message' => $bloodRequest->message,
                'reason' => $bloodRequest->reason,
                'status' => 'pending',
            ]);
        });

        if ($donor->user_id) {
            \App\Helpers\NotificationHelper::bloodRequest(
                $donor->user_id,
                $bloodRequest->id,
                Auth::id(),
                $donor->id,
                Auth::user()->name,
                $bloodRequest->blood_group,
                $bloodRequest->hospital,
                $bloodRequest->urgency
            );

            if ($bloodRequest->urgency === 'critical') {
                \App\Helpers\NotificationHelper::emergency(
                    $donor->user_id,
                    $bloodRequest->id,
                    Auth::id(),
                    $donor->id,
                    Auth::user()->name,
                    $bloodRequest->blood_group,
                    $bloodRequest->hospital,
                    $bloodRequest->city
                );
            }
        }

        return redirect()->route('blood.search', ['request_id' => $bloodRequest->id])
            ->with('success', 'Blood request sent directly to ' . $donor->name . '. 🩸');
    }

    public function create(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        if (Auth::user()->role !== 'user') {
            abort(403, 'Only Blood Need users can create blood requests.');
        }

        $donor = null;
        if ($request->filled('donor')) {
            $donor = Donor::where('id', $request->integer('donor'))
                ->where('is_available', true)
                ->first();

            if ($donor && !$donor->canDonate()) {
                $donor = null;
            }
        }

        return view('blood-request', compact('donor'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        if (Auth::user()->role !== 'user') {
            abort(403, 'Only Blood Need users can create blood requests.');
        }

        $validated = $request->validate([
            'patient_name' => ['required', 'string', 'max:255'],
            'requester_type' => ['required', 'in:relative,hospital'],
            'request_for' => ['required', 'in:self,relative'],
            'blood_group' => ['required', 'string', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'city' => ['required', 'string', 'max:100'],
            'area' => ['required', 'string', 'max:120'],
            'hospital' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'string', 'max:20'],
            'units' => ['required', 'integer', 'min:1', 'max:20'],
            'urgency' => ['required', 'string', 'in:normal,urgent,critical'],
            'emergency_mode' => ['nullable', 'boolean'],
            'message' => ['nullable', 'string', 'max:2000'],
            'reason' => ['required', 'in:accident,dialysis,surgery,pregnancy,regular_treatment,other'],
            'donor_id' => ['nullable', 'integer', 'exists:donors,id'],
        ]);

        $selectedDonor = null;
        if (!empty($validated['donor_id'])) {
            $selectedDonor = Donor::findOrFail($validated['donor_id']);

            if (!$selectedDonor->isAvailable() || !$selectedDonor->canDonate()) {
                return back()->withInput()->with('error', 'The selected donor is currently unavailable. Please choose another donor.');
            }

            if (!$selectedDonor->matchesBloodGroup($validated['blood_group'])) {
                return back()->withInput()->with('error', 'The selected donor blood group does not match the requested blood group.');
            }
        }

        // Every blood need is a master request. Individual donors are tracked
        // as child donor-assignment records so one need can be fulfilled by
        // multiple donors (including donors from different cities).
        $bloodRequest = BloodRequest::create([
            'user_id' => Auth::id(),
            'donor_id' => null,
            'parent_request_id' => null,
            'patient_name' => $validated['patient_name'],
            'requester_type' => $validated['requester_type'],
            'request_for' => $validated['request_for'],
            'blood_group' => $validated['blood_group'],
            'city' => trim($validated['city']),
            'area' => trim($validated['area']),
            'hospital' => $validated['hospital'],
            'contact' => $validated['contact'],
            'contact_phone' => $validated['contact'],
            'units' => $validated['units'],
            'units_fulfilled' => 0,
            'urgency' => $validated['urgency'],
            'emergency_mode' => $validated['emergency_mode'] ?? false,
            'message' => $validated['message'] ?? null,
            'reason' => $validated['reason'] ?? null,
            'status' => 'pending',
        ]);

        if ($selectedDonor) {
            // Pre-create a donor assignment in pending state. The donor must
            // still accept it before it becomes an active donation.
            BloodRequest::create([
                'user_id' => Auth::id(),
                'donor_id' => $selectedDonor->id,
                'parent_request_id' => $bloodRequest->id,
                'patient_name' => $bloodRequest->patient_name,
                'requester_type' => $bloodRequest->requester_type,
                'request_for' => $bloodRequest->request_for,
                'blood_group' => $bloodRequest->blood_group,
                'city' => $bloodRequest->city,
                'area' => $bloodRequest->area,
                'hospital' => $bloodRequest->hospital,
                'contact' => $bloodRequest->contact,
                'contact_phone' => $bloodRequest->contact_phone,
                'units' => min($bloodRequest->remainingUnits(), 1),
                'units_fulfilled' => 0,
                'urgency' => $bloodRequest->urgency,
                'emergency_mode' => $bloodRequest->emergency_mode,
                'message' => $bloodRequest->message,
                'reason' => $bloodRequest->reason,
                'status' => 'pending',
            ]);
        }

        if ($selectedDonor?->user_id) {
            NotificationHelper::bloodRequest(
                $selectedDonor->user_id,
                $bloodRequest->id,
                Auth::id(),
                $selectedDonor->id,
                Auth::user()->name,
                $bloodRequest->blood_group,
                $bloodRequest->hospital,
                $bloodRequest->urgency
            );

            if ($bloodRequest->urgency === 'critical') {
                NotificationHelper::emergency(
                    $selectedDonor->user_id,
                    $bloodRequest->id,
                    Auth::id(),
                    $selectedDonor->id,
                    Auth::user()->name,
                    $bloodRequest->blood_group,
                    $bloodRequest->hospital,
                    $bloodRequest->city
                );
            }
        }

        return redirect()->route('blood.requests.mine')->with(
            'success',
            $selectedDonor
                ? 'Blood request sent directly to ' . $selectedDonor->name . '. 🩸'
                : 'Blood request submitted successfully! 🩸'
        );
    }

    public function myRequests()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        if (Auth::user()->role !== 'user') {
            abort(403, 'Only Blood Need users can view their requests.');
        }

        $requests = BloodRequest::with(['donor', 'donorAssignments.donor'])->where('user_id', Auth::id())->whereNull('parent_request_id')->latest()->get();
        return view('my-requests', compact('requests'));
    }

    public function cancel(BloodRequest $bloodRequest)
    {
        abort_unless($bloodRequest->user_id === Auth::id(), 403);
        if ($bloodRequest->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be cancelled.');
        }
        $bloodRequest->update(['status' => 'cancelled']);
        return back()->with('success', 'Blood request cancelled.');
    }
}
