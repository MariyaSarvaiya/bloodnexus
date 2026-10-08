<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use App\Models\BloodRequest;
use App\Models\Notification;
use App\Models\BloodInventoryTransaction;
use Illuminate\Http\Request;
use App\Support\BloodCompatibility;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DonorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | GET AUTHENTICATED DONOR
    |--------------------------------------------------------------------------
    */

    private function donor()
    {
        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | USER MUST BE LOGGED IN
        |--------------------------------------------------------------------------
        */

        if (!$user) {
            abort(403, 'Authentication required.');
        }


        /*
        |--------------------------------------------------------------------------
        | ONLY DONOR ROLE
        |--------------------------------------------------------------------------
        */

        if ($user->role !== 'donor') {
            abort(
                403,
                'This area is only for donor users.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FIND DONOR PROFILE
        |--------------------------------------------------------------------------
        */

        $donor = Donor::where(
            'user_id',
            $user->id
        )->first();


        if (!$donor) {
            abort(
                403,
                'Donor profile not found.'
            );
        }


        return $donor;
    }


    /*
    |--------------------------------------------------------------------------
    | DONOR DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function showProfile(Donor $donor)
    {
        abort_unless(Auth::check() && Auth::user()->role === 'user', 403, 'Only Blood Need users can view donor profiles.');
        return view('donor-public-profile', compact('donor'));
    }

    public function dashboard()
    {
        $user = Auth::user();

        $donor = $this->donor();


        /*
        |--------------------------------------------------------------------------
        | DONATION ELIGIBILITY
        |--------------------------------------------------------------------------
        */

        $canDonate = $donor->canDonate();

        $nextDonationDate = $donor->nextDonationDate();

        $cooldownDays = $donor->donationCooldownDays();


        /*
        |--------------------------------------------------------------------------
        | DONOR BLOOD GROUP
        |--------------------------------------------------------------------------
        */

        $bloodGroup = trim(
            $donor->blood_group
            ?: ($user->blood_group ?? '')
        );


        /*
        |--------------------------------------------------------------------------
        | DONOR CITY
        |--------------------------------------------------------------------------
        */

        $city = trim(
            $donor->city
            ?: ($user->city ?? '')
        );


        /*
        |--------------------------------------------------------------------------
        | DONOR REQUEST HISTORY
        |--------------------------------------------------------------------------
        */

        $myRequestsQuery = BloodRequest::where(
            'donor_id',
            $donor->id
        );


        /*
        |--------------------------------------------------------------------------
        | TOTAL COMPLETED DONATIONS
        |--------------------------------------------------------------------------
        */

        $totalDonations = (clone $myRequestsQuery)
            ->where('status', 'completed')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | PENDING ASSIGNED REQUESTS
        |--------------------------------------------------------------------------
        */

        $pendingRequests = (clone $myRequestsQuery)
            ->where('status', 'pending')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | ACCEPTED REQUESTS
        |--------------------------------------------------------------------------
        */

        $acceptedRequests = (clone $myRequestsQuery)
            ->where('status', 'accepted')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | COMPLETED REQUESTS
        |--------------------------------------------------------------------------
        */

        $completedRequests = (clone $myRequestsQuery)
            ->where('status', 'completed')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | MATCHING BLOOD REQUESTS
        |--------------------------------------------------------------------------
        |
        | Donor can see requests ONLY if:
        |
        | 1. Donor is available
        | 2. Donor is not in 3-month cooldown
        | 3. Blood group matches
        | 4. City matches
        | 5. Request is pending
        | 6. Request has no donor
        |
        */

        $nearbyBloodRequests = collect();


        if (
            $bloodGroup !== '' &&
            $donor->isAvailable() &&
            $canDonate
        ) {

            $normalizedCity = strtolower(preg_replace('/\s+/', '', $city));

            $compatibleGroups = BloodCompatibility::recipientGroupsForDonor($bloodGroup);

            $nearbyBloodRequests = BloodRequest::with('user')
                ->where('status', 'pending')
                ->whereIn(DB::raw('UPPER(TRIM(blood_group))'), $compatibleGroups)
                ->whereNull('donor_id')
                // Show matching requests from EVERY city/area. The donor's city
                // is only used as a visual priority, never as a hard filter.
                ->when($normalizedCity !== '', function ($query) use ($normalizedCity) {
                    $query->orderByRaw(
                        "CASE WHEN REPLACE(LOWER(TRIM(city)), ' ', '') = ? THEN 0 ELSE 1 END",
                        [$normalizedCity]
                    );
                })
                ->orderByRaw("CASE WHEN urgency = 'critical' THEN 1 WHEN urgency = 'urgent' THEN 2 WHEN urgency = 'normal' THEN 3 ELSE 4 END")
                ->latest()
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | URGENT REQUEST COUNT
        |--------------------------------------------------------------------------
        */

        $urgentRequests = $nearbyBloodRequests
            ->whereIn(
                'urgency',
                [
                    'urgent',
                    'critical'
                ]
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | TOTAL NEARBY REQUESTS
        |--------------------------------------------------------------------------
        */

        $nearbyRequests = $nearbyBloodRequests->count();


        /*
        |--------------------------------------------------------------------------
        | REQUESTS CURRENTLY ACCEPTED BY THIS DONOR
        |--------------------------------------------------------------------------
        */

        $acceptedBloodRequests = BloodRequest::with('user')
            ->where(
                'donor_id',
                $donor->id
            )
            ->where(
                'status',
                'accepted'
            )
            ->latest()
            ->get();


        /*
        |--------------------------------------------------------------------------
        | RECENT DONOR HISTORY
        |--------------------------------------------------------------------------
        */

        $recentRequests = BloodRequest::with('user')
            ->where(
                'donor_id',
                $donor->id
            )
            ->latest()
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | UNREAD NOTIFICATIONS
        |--------------------------------------------------------------------------
        */

        $unreadNotifications = Notification::where(
            'user_id',
            $user->id
        )
            ->where(
                'is_read',
                false
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | RETURN DONOR DASHBOARD
        |--------------------------------------------------------------------------
        */

        return view(
            'donor-dashboard',
            [

                'user' => $user,

                'donor' => $donor,

                'bloodGroup' => $bloodGroup,

                'city' => $city,

                'totalDonations' => $totalDonations,

                'pendingRequests' => $pendingRequests,

                'acceptedRequests' => $acceptedRequests,

                'completedRequests' => $completedRequests,

                'urgentRequests' => $urgentRequests,

                'nearbyRequests' => $nearbyRequests,

                'nearbyBloodRequests' => $nearbyBloodRequests,

                'acceptedBloodRequests' => $acceptedBloodRequests,

                'recentRequests' => $recentRequests,

                'unreadNotifications' => $unreadNotifications,

                'canDonate' => $canDonate,

                'nextDonationDate' => $nextDonationDate,

                'cooldownDays' => $cooldownDays,

            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DONOR REQUESTS
    |--------------------------------------------------------------------------
    */

    public function requests()
    {
        $donor = $this->donor();


        /*
        |--------------------------------------------------------------------------
        | COOLDOWN STATUS
        |--------------------------------------------------------------------------
        */

        $canDonate = $donor->canDonate();

        $nextDonationDate = $donor->nextDonationDate();

        $cooldownDays = $donor->donationCooldownDays();


        /*
        |--------------------------------------------------------------------------
        | DONOR REQUEST HISTORY
        |--------------------------------------------------------------------------
        */

        $donorCity = strtolower(
            preg_replace('/\s+/', '', trim($donor->city ?: (Auth::user()->city ?? '')))
        );

        $donorBloodGroup = strtoupper(trim($donor->blood_group ?? ''));
        $compatibleGroups = BloodCompatibility::recipientGroupsForDonor($donorBloodGroup);

        $requests = BloodRequest::with(['user', 'parentRequest'])
            ->whereIn(DB::raw('UPPER(TRIM(blood_group))'), $compatibleGroups)
            ->where(function ($query) use ($donor) {
                $query->where(function ($q) {
                    $q->whereNull('parent_request_id')->whereNull('donor_id');
                })->orWhere(function ($q) use ($donor) {
                    $q->where('parent_request_id', '!=', null)->where('donor_id', $donor->id);
                });
            })
            ->whereIn('status', ['pending', 'accepted', 'completed'])
            ->when($donorCity !== '', function ($query) use ($donorCity) {
                // City is a ranking preference, not a hard filter.
                $query->orderByRaw(
                    "CASE WHEN REPLACE(LOWER(TRIM(city)), ' ', '') = ? THEN 0 ELSE 1 END",
                    [$donorCity]
                );
            })
            ->orderByRaw("CASE WHEN urgency = 'critical' THEN 1 WHEN urgency = 'urgent' THEN 2 WHEN urgency = 'normal' THEN 3 ELSE 4 END")
            ->latest()
            ->get();

        $requests->transform(function ($request) use ($donor, $donorCity) {
            $score = 50;
            $reasons = [];
            if (BloodCompatibility::canDonateTo($donor->blood_group, $request->blood_group)) {
                $score += strtoupper(trim($request->blood_group)) === strtoupper(trim($donor->blood_group)) ? 25 : 20;
                $reasons[] = strtoupper(trim($request->blood_group)) === strtoupper(trim($donor->blood_group)) ? 'Exact blood group match' : 'Compatible blood group';
            }
            if ($donorCity !== '' && strtolower(preg_replace('/\s+/', '', trim($request->city))) === $donorCity) { $score += 15; $reasons[] = 'Same city'; }
            if ($request->urgency === 'critical' || $request->emergency_mode) { $score += 10; $reasons[] = 'Critical emergency priority'; }
            elseif ($request->urgency === 'urgent') { $score += 6; $reasons[] = 'Urgent priority'; }
            $request->ai_match_score = min(100, $score);
            $request->ai_match_reason = implode(' • ', $reasons ?: ['Compatible request']);
            return $request;
        });

        return view(
            'donor-requests',
            compact(
                'requests',
                'donor',
                'canDonate',
                'nextDonationDate',
                'cooldownDays'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ACCEPT / CLAIM BLOOD REQUEST
    |--------------------------------------------------------------------------
    */

    public function accept(
        BloodRequest $bloodRequest
    ) {
        $donor = $this->donor();

        if (!$donor->canDonate()) {
            $nextDate = $donor->nextDonationDate();
            return back()->with('error', '🩸 You cannot donate blood yet. Your next donation date is ' . $nextDate->format('d M Y') . '.');
        }

        if (!$donor->is_available) {
            return back()->with('error', 'You are currently unavailable for blood requests.');
        }

        DB::transaction(function () use ($bloodRequest, $donor) {
            $master = $bloodRequest->parent_request_id
                ? $bloodRequest->parentRequest()->lockForUpdate()->first()
                : BloodRequest::where('id', $bloodRequest->id)->lockForUpdate()->first();

            abort_unless($master, 404, 'Blood request not found.');

            if (!$master->isMasterRequest()) {
                abort(409, 'Invalid master blood request.');
            }

            if ($master->status === 'completed' || $master->remainingUnits() <= 0) {
                abort(409, 'This blood need has already been completely fulfilled.');
            }

            if (!BloodCompatibility::canDonateTo($donor->blood_group, $master->blood_group)) {
                abort(403, 'This blood request is not compatible with your blood group.');
            }

            $activeAssignment = BloodRequest::where('parent_request_id', $master->id)
                ->where('donor_id', $donor->id)
                ->whereIn('status', ['pending', 'accepted'])
                ->lockForUpdate()
                ->first();

            if ($activeAssignment && $activeAssignment->status === 'accepted') {
                abort(409, 'You have already accepted this blood need.');
            }

            if (!$activeAssignment) {
                $reservedUnits = BloodRequest::where('parent_request_id', $master->id)
                    ->whereIn('status', ['pending', 'accepted'])
                    ->sum('units');

                $availableUnits = max(0, (int) $master->units - (int) $master->units_fulfilled - (int) $reservedUnits);
                if ($availableUnits <= 0) {
                    abort(409, 'All required units are already reserved by other donors.');
                }
            }

            $assignment = $activeAssignment ?: BloodRequest::create([
                'user_id' => $master->user_id,
                'donor_id' => $donor->id,
                'parent_request_id' => $master->id,
                'patient_name' => $master->patient_name,
                'requester_type' => $master->requester_type,
                'request_for' => $master->request_for,
                'blood_group' => $master->blood_group,
                'city' => $master->city,
                'area' => $master->area,
                'hospital' => $master->hospital,
                'contact' => $master->contact,
                'contact_phone' => $master->contact_phone,
                'units' => 1,
                'units_fulfilled' => 0,
                'urgency' => $master->urgency,
                'emergency_mode' => $master->emergency_mode,
                'message' => $master->message,
                'reason' => $master->reason,
                'status' => 'pending',
            ]);

            $assignment->update(['status' => 'accepted']);
            $donor->update(['is_available' => false]);

            Notification::create([
                'user_id' => $master->user_id,
                'type' => 'accepted',
                'title' => '🩸 Donor Accepted Your Blood Need',
                'message' => $donor->name . ' from ' . ($donor->city ?: 'another city') . ' accepted your ' . $master->blood_group . ' blood need. Your request can still receive additional donors if more units are required.',
                'data' => [
                    'blood_request_id' => $master->id,
                    'donor_assignment_id' => $assignment->id,
                    'donor_id' => $donor->id,
                ],
                'is_read' => false,
            ]);
        });

        return redirect()->route('donor.dashboard')->with('success', '❤️ You accepted this blood need. Your donation slot is now reserved.');
    }


    /*
    |--------------------------------------------------------------------------
    | REJECT / SKIP REQUEST
    |--------------------------------------------------------------------------
    */

    public function reject(
        BloodRequest $bloodRequest
    ) {

        $donor = $this->donor();

        if ($bloodRequest->status === 'accepted' && (int) $bloodRequest->donor_id === (int) $donor->id) {
            return back()->with('error', 'Once you accept a blood request, it cannot be rejected. Please complete the donation or contact admin.');
        }

        /*
        |--------------------------------------------------------------------------
        | IF REQUEST BELONGS TO THIS DONOR
        |--------------------------------------------------------------------------
        */

        if (
            $bloodRequest->donor_id !== null
            &&
            (int) $bloodRequest->donor_id
            === (int) $donor->id
        ) {

            $bloodRequest->update(
                [

                    'donor_id' => null,

                    'status' => 'pending',

                ]
            );


            /*
            |--------------------------------------------------------------------------
            | DONOR AVAILABLE AGAIN
            |--------------------------------------------------------------------------
            */

            if ($donor->canDonate()) {

                $donor->update(
                    [
                        'is_available' => true,
                    ]
                );
            }
        }


        return redirect()

            ->route(
                'donor.dashboard'
            )

            ->with(
                'success',
                'Request skipped. It remains available for another matching donor.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | COMPLETE DONATION
    |--------------------------------------------------------------------------
    */

    public function complete(
        BloodRequest $bloodRequest
    ) {
        $donor = $this->donor();

        DB::transaction(function () use ($bloodRequest, $donor) {
            $master = $bloodRequest->parent_request_id
                ? $bloodRequest->parentRequest()->lockForUpdate()->first()
                : BloodRequest::where('id', $bloodRequest->id)->lockForUpdate()->first();

            abort_unless($master, 404, 'Blood request not found.');

            $assignment = BloodRequest::where('parent_request_id', $master->id)
                ->where('donor_id', $donor->id)
                ->where('status', 'accepted')
                ->lockForUpdate()
                ->first();

            if (!$assignment) {
                // Legacy requests created before the master/assignment model.
                if ($bloodRequest->parent_request_id === null && (int) $bloodRequest->donor_id === (int) $donor->id && $bloodRequest->status === 'accepted') {
                    $assignment = $bloodRequest;
                    $master = $bloodRequest;
                } else {
                    abort(403, 'This donation assignment does not belong to you.');
                }
            }

            if ($master->status === 'completed' || $master->remainingUnits() <= 0) {
                return;
            }

            $assignment->update([
                'status' => 'completed',
                'units_fulfilled' => 1,
                'donation_date' => now()->toDateString(),
                'donation_time' => now()->format('H:i:s'),
                'completed_at' => now(),
            ]);

            // Live inventory audit: every completed donor contribution is recorded
            // as received, and the fulfilled blood unit is recorded as issued.
            BloodInventoryTransaction::create([
                'type' => 'received',
                'blood_group' => $master->blood_group,
                'units' => 1,
                'donor_id' => $donor->id,
                'blood_request_id' => $master->id,
                'transaction_at' => now(),
                'source' => 'Donor donation',
                'note' => 'Blood received from completed donor donation.',
            ]);
            BloodInventoryTransaction::create([
                'type' => 'issued',
                'blood_group' => $master->blood_group,
                'units' => 1,
                'donor_id' => $donor->id,
                'blood_request_id' => $master->id,
                'transaction_at' => now(),
                'source' => 'Blood need fulfillment',
                'note' => 'Blood unit issued against fulfilled blood need.',
            ]);

            $newFulfilled = min((int) $master->units, (int) $master->units_fulfilled + 1);
            $fullyFulfilled = $newFulfilled >= (int) $master->units;

            $master->update([
                'units_fulfilled' => $newFulfilled,
                'status' => $fullyFulfilled ? 'completed' : 'pending',
                'fulfilled_at' => $fullyFulfilled ? now() : null,
                'completed_at' => $fullyFulfilled ? now() : null,
                'closure_reason' => $fullyFulfilled ? 'All required blood units fulfilled.' : null,
            ]);

            $donor->update([
                'last_donation_date' => now()->toDateString(),
                'is_available' => false,
            ]);

            $nextDate = $donor->fresh()->nextDonationDate();

            Notification::create([
                'user_id' => $master->user_id,
                'type' => $fullyFulfilled ? 'completed' : 'partial_completed',
                'title' => $fullyFulfilled ? '🎉 Blood Need Fulfilled' : '🩸 Blood Donation Received',
                'message' => $fullyFulfilled
                    ? 'Your ' . $master->blood_group . ' blood need at ' . $master->hospital . ' has been completely fulfilled. All remaining donor requests are now closed.'
                    : 'A donor has completed 1 unit for your ' . $master->blood_group . ' blood need. ' . $master->remainingUnits() . ' unit(s) are still required.',
                'data' => [
                    'blood_request_id' => $master->id,
                    'donor_assignment_id' => $assignment->id,
                    'donor_id' => $donor->id,
                    'units_fulfilled' => $newFulfilled,
                    'units_required' => $master->units,
                    'remaining_units' => $master->remainingUnits(),
                ],
                'is_read' => false,
            ]);

            Notification::create([
                'user_id' => $donor->user_id,
                'type' => 'completed',
                'title' => '❤️ Donation Recorded',
                'message' => 'Your donation for ' . $master->patient_name . ' at ' . $master->hospital . ' has been recorded. Next eligible donation date: ' . $nextDate->format('d M Y') . '.',
                'data' => ['blood_request_id' => $master->id, 'donor_assignment_id' => $assignment->id],
                'is_read' => false,
            ]);

            if ($fullyFulfilled) {
                // Notify donors who had already accepted/pending assignments and
                // make their outstanding assignment records read-only/closed.
                $otherAssignments = BloodRequest::where('parent_request_id', $master->id)
                    ->where('id', '!=', $assignment->id)
                    ->whereIn('status', ['pending', 'accepted'])
                    ->get();

                foreach ($otherAssignments as $other) {
                    $wasAccepted = $other->status === 'accepted';
                    $other->update([
                        'status' => 'cancelled',
                        'closure_reason' => 'Blood need already fulfilled by another donor.',
                    ]);

                    if ($other->donor_id) {
                        $otherDonor = Donor::find($other->donor_id);
                        if ($otherDonor && $wasAccepted) {
                            // A donor that had accepted but did not complete should
                            // be released only when the master need is fulfilled.
                            $otherDonor->update(['is_available' => true]);
                        }
                        $otherUserId = $otherDonor?->user_id;
                        if ($otherUserId) {
                            Notification::create([
                                'user_id' => $otherUserId,
                                'type' => 'request_closed',
                                'title' => 'ℹ️ Blood Need Fulfilled',
                                'message' => 'This blood need was fulfilled by another donor. No further donation is required for this request.',
                                'data' => ['blood_request_id' => $master->id],
                                'is_read' => false,
                            ]);
                        }
                    }
                }
            }
        });

        return redirect()->route('donor.dashboard')->with('success', '🎉 Donation completed successfully. The blood seeker has been updated automatically.');
    }


    /*
    |--------------------------------------------------------------------------
    | TOGGLE DONOR AVAILABILITY
    |--------------------------------------------------------------------------
    */

    public function updateMedicalHistory(Request $request)
    {
        $donor = $this->donor();
        $data = $request->validate([
            'medical_history' => ['required', 'array'],
            'medical_history.*' => ['required', 'in:yes,no'],
        ]);

        $donor->update([
            'medical_history' => json_encode($data['medical_history']),
        ]);
        return back()->with('success', 'Medical history updated successfully.');
    }

    public function toggleAvailability()
    {

        $donor = $this->donor();


        /*
        |--------------------------------------------------------------------------
        | COOLDOWN CHECK
        |--------------------------------------------------------------------------
        */

        if (!$donor->canDonate()) {

            $nextDate = $donor->nextDonationDate();

            return redirect()

                ->route(
                    'donor.dashboard'
                )

                ->with(
                    'error',

                    '⏳ You are currently in the 3-month donation recovery period. ' .
                    'You can donate again from ' .
                    $nextDate->format('d M Y') .
                    '.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | TOGGLE
        |--------------------------------------------------------------------------
        */

        $newAvailability = !$donor->is_available;


        $donor->update(
            [
                'is_available' => $newAvailability,
            ]
        );


        return redirect()

            ->route(
                'donor.dashboard'
            )

            ->with(
                'success',

                $newAvailability

                    ? '🟢 You are now available for blood requests.'

                    : '⚪ You are now unavailable for blood requests.'
            );
    }
}