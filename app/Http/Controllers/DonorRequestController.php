<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use App\Models\BloodRequest;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonorRequestController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | REQUEST FORM
    |--------------------------------------------------------------------------
    */

    public function create(Donor $donor)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Only Blood Need users can request donors
        if (Auth::user()->role !== 'user') {
            abort(403, 'Only Blood Need users can request donors.');
        }

        if (!$donor->is_available) {
            return back()->with(
                'error',
                'This donor is currently unavailable.'
            );
        }

        if ($donor->user_id == Auth::id()) {
            return back()->with(
                'error',
                'You cannot request yourself as a donor.'
            );
        }

        return view('donor-request', compact('donor'));
    }


    /*
    |--------------------------------------------------------------------------
    | SEND BLOOD REQUEST
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, Donor $donor)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // ONLY PHASE 1 USER CAN SEND REQUEST
        if (Auth::user()->role !== 'user') {
            abort(403, 'Only Blood Need users can send blood requests.');
        }

        if (!$donor->is_available) {
            return back()->with(
                'error',
                'This donor is currently unavailable.'
            );
        }

        if ($donor->user_id == Auth::id()) {
            return back()->with(
                'error',
                'You cannot request yourself as a donor.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PREVENT DUPLICATE ACTIVE REQUEST
        |--------------------------------------------------------------------------
        */

        $existingRequest = BloodRequest::where(
            'user_id',
            Auth::id()
        )
        ->where(
            'donor_id',
            $donor->id
        )
        ->whereIn(
            'status',
            ['pending', 'accepted']
        )
        ->exists();

        if ($existingRequest) {
            return back()->with(
                'error',
                'You already have an active request for this donor.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATE
        |--------------------------------------------------------------------------
        */

        $data = $request->validate([
            'patient_name' => 'required|string|max:255',
            'blood_group' => 'required|string|max:10',
            'hospital' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'urgency' => 'required|in:normal,urgent,critical',
            'reason' => 'nullable|string',
            'contact_phone' => 'required|string|max:20',
        ]);


        /*
        |--------------------------------------------------------------------------
        | CREATE REQUEST
        |--------------------------------------------------------------------------
        */

        $bloodRequest = BloodRequest::create([
            'user_id' => Auth::id(),
            'donor_id' => $donor->id,
            'patient_name' => $data['patient_name'],
            'blood_group' => $data['blood_group'],
            'hospital' => $data['hospital'],
            'city' => $data['city'],
            'urgency' => $data['urgency'],
            'reason' => $data['reason'] ?? null,
            'contact_phone' => $data['contact_phone'],
            'status' => 'pending',
        ]);


        /*
        |--------------------------------------------------------------------------
        | NOTIFY DONOR
        |--------------------------------------------------------------------------
        */

        Notification::create([
            'user_id' => $donor->user_id,
            'type' => 'blood_request',
            'title' => 'New Blood Request',
            'message' =>
                Auth::user()->name .
                ' has requested ' .
                $bloodRequest->blood_group .
                ' blood at ' .
                $bloodRequest->hospital .
                '. Urgency: ' .
                ucfirst($bloodRequest->urgency) .
                '.',
            'data' => [
                'blood_request_id' => $bloodRequest->id,
                'requester_id' => Auth::id(),
                'donor_id' => $donor->id,
            ],
            'is_read' => false,
        ]);


        return redirect()
            ->route('dashboard')
            ->with(
                'success',
                '🩸 Blood request sent successfully. The donor has been notified.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DONOR INCOMING REQUESTS
    |--------------------------------------------------------------------------
    */

    public function incomingRequests()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // ONLY DONOR
        if (Auth::user()->role !== 'donor') {
            abort(403, 'Only donors can view donor requests.');
        }

        $donor = Donor::where(
            'user_id',
            Auth::id()
        )->first();

        if (!$donor) {
            return redirect()
                ->route('donor.dashboard')
                ->with(
                    'error',
                    'Donor profile not found.'
                );
        }

        $requests = BloodRequest::with('user')
            ->where(
                'donor_id',
                $donor->id
            )
            ->whereIn(
                'status',
                ['pending', 'accepted']
            )
            ->latest()
            ->get();

        return view(
            'donor-requests',
            compact('requests')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ACCEPT REQUEST
    |--------------------------------------------------------------------------
    */

    public function accept(BloodRequest $bloodRequest)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'donor') {
            abort(403, 'Only donors can accept requests.');
        }

        $donor = Donor::where(
            'user_id',
            Auth::id()
        )->first();

        if (
            !$donor ||
            $bloodRequest->donor_id != $donor->id
        ) {
            abort(403);
        }

        if ($bloodRequest->status !== 'pending') {
            return back()->with(
                'error',
                'This request is no longer available.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ACCEPT
        |--------------------------------------------------------------------------
        */

        $bloodRequest->update([
            'status' => 'accepted',
        ]);

        $donor->update([
            'is_available' => false,
        ]);


        /*
        |--------------------------------------------------------------------------
        | NOTIFY REQUESTER
        |--------------------------------------------------------------------------
        */

        Notification::create([
            'user_id' => $bloodRequest->user_id,
            'type' => 'accepted',
            'title' => 'Blood Request Accepted',
            'message' =>
                $donor->name .
                ' has accepted your blood request for ' .
                $bloodRequest->blood_group .
                ' blood. Please contact the donor.',
            'data' => [
                'blood_request_id' => $bloodRequest->id,
                'donor_id' => $donor->id,
            ],
            'is_read' => false,
        ]);

        return back()->with(
            'success',
            '✅ Blood request accepted. The requester has been notified.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REJECT REQUEST
    |--------------------------------------------------------------------------
    */

    public function reject(BloodRequest $bloodRequest)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'donor') {
            abort(403, 'Only donors can reject requests.');
        }

        $donor = Donor::where(
            'user_id',
            Auth::id()
        )->first();

        if (
            !$donor ||
            $bloodRequest->donor_id != $donor->id
        ) {
            abort(403);
        }

        if ($bloodRequest->status !== 'pending') {
            return back()->with(
                'error',
                'This request is no longer available.'
            );
        }

        $bloodRequest->update([
            'status' => 'cancelled',
        ]);


        /*
        |--------------------------------------------------------------------------
        | NOTIFY REQUESTER
        |--------------------------------------------------------------------------
        */

        Notification::create([
            'user_id' => $bloodRequest->user_id,
            'type' => 'rejected',
            'title' => 'Blood Request Rejected',
            'message' =>
                $donor->name .
                ' has rejected your blood request for ' .
                $bloodRequest->blood_group .
                ' blood.',
            'data' => [
                'blood_request_id' => $bloodRequest->id,
                'donor_id' => $donor->id,
            ],
            'is_read' => false,
        ]);

        return back()->with(
            'success',
            '❌ Blood request rejected. The requester has been notified.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | COMPLETE DONATION
    |--------------------------------------------------------------------------
    */

    public function complete(BloodRequest $bloodRequest)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'donor') {
            abort(403, 'Only donors can complete donations.');
        }

        $donor = Donor::where(
            'user_id',
            Auth::id()
        )->first();

        if (
            !$donor ||
            $bloodRequest->donor_id != $donor->id
        ) {
            abort(403);
        }

        if ($bloodRequest->status !== 'accepted') {
            return back()->with(
                'error',
                'Only an accepted request can be completed.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | COMPLETE
        |--------------------------------------------------------------------------
        */

        $bloodRequest->update([
            'status' => 'completed',
        ]);

        // Donor available again
        $donor->update([
            'is_available' => true,
        ]);


        /*
        |--------------------------------------------------------------------------
        | NOTIFY REQUESTER
        |--------------------------------------------------------------------------
        */

        Notification::create([
            'user_id' => $bloodRequest->user_id,
            'type' => 'completed',
            'title' => 'Donation Completed',
            'message' =>
                $donor->name .
                ' has completed the blood donation process.',
            'data' => [
                'blood_request_id' => $bloodRequest->id,
                'donor_id' => $donor->id,
            ],
            'is_read' => false,
        ]);

        return back()->with(
            'success',
            '🎉 Donation marked as completed. You are available again.'
        );
    }
}