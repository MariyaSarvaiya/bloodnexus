<?php

namespace App\Observers;

use App\Models\BloodRequest;
use App\Models\Notification;

class BloodRequestObserver
{
    public function created(BloodRequest $bloodRequest): void
    {
        // New blood request created
        // Matching donor notification will be handled
        // when donor is assigned.
    }


    public function updated(BloodRequest $bloodRequest): void
    {
        /*
        |--------------------------------------------------------------------------
        | DONOR ASSIGNED
        |--------------------------------------------------------------------------
        */

        if (
            $bloodRequest->wasChanged('donor_id') &&
            $bloodRequest->donor_id
        ) {

            $donor = $bloodRequest->donor;

            if ($donor && $donor->user_id) {

                Notification::create([

                    'user_id' => $donor->user_id,

                    'type' => 'donor_request',

                    'title' => '🩸 New Blood Donation Request',

                    'message' =>
                        'A patient needs ' .
                        $bloodRequest->blood_group .
                        ' blood in ' .
                        $bloodRequest->city .
                        '. Please check your donor requests.',

                    'data' => [
                        'blood_request_id' => $bloodRequest->id,
                        'blood_group' => $bloodRequest->blood_group,
                        'city' => $bloodRequest->city,
                        'urgency' => $bloodRequest->urgency,
                    ],

                    'is_read' => false,
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | DONOR ACCEPTED
        |--------------------------------------------------------------------------
        */

        if (
            $bloodRequest->wasChanged('status') &&
            $bloodRequest->status === 'accepted'
        ) {

            if ($bloodRequest->user_id) {

                Notification::create([

                    'user_id' => $bloodRequest->user_id,

                    'type' => 'accepted',

                    'title' => '✅ Donor Accepted Your Request',

                    'message' =>
                        'Good news! A donor has accepted your blood request.',

                    'data' => [
                        'blood_request_id' => $bloodRequest->id,
                        'donor_id' => $bloodRequest->donor_id,
                    ],

                    'is_read' => false,
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | REQUEST REJECTED
        |--------------------------------------------------------------------------
        */

        if (
            $bloodRequest->wasChanged('status') &&
            $bloodRequest->status === 'cancelled'
        ) {

            if ($bloodRequest->user_id) {

                Notification::create([

                    'user_id' => $bloodRequest->user_id,

                    'type' => 'rejected',

                    'title' => '❌ Blood Request Update',

                    'message' =>
                        'Your blood request was cancelled or rejected.',

                    'data' => [
                        'blood_request_id' => $bloodRequest->id,
                    ],

                    'is_read' => false,
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | DONATION COMPLETED
        |--------------------------------------------------------------------------
        */

        if (
            $bloodRequest->wasChanged('status') &&
            $bloodRequest->status === 'completed'
        ) {

            // Notify User

            if ($bloodRequest->user_id) {

                Notification::create([

                    'user_id' => $bloodRequest->user_id,

                    'type' => 'completed',

                    'title' => '🎉 Blood Donation Completed',

                    'message' =>
                        'Your blood donation request has been completed successfully.',

                    'data' => [
                        'blood_request_id' => $bloodRequest->id,
                    ],

                    'is_read' => false,
                ]);
            }


            // Notify Donor

            if ($bloodRequest->donor_id) {

                $donor = $bloodRequest->donor;

                if ($donor && $donor->user_id) {

                    Notification::create([

                        'user_id' => $donor->user_id,

                        'type' => 'completed',

                        'title' => '🎉 Donation Completed',

                        'message' =>
                            'Your blood donation has been marked as completed. Thank you for helping save a life! ❤️',

                        'data' => [
                            'blood_request_id' => $bloodRequest->id,
                        ],

                        'is_read' => false,
                    ]);
                }
            }
        }
    }
}