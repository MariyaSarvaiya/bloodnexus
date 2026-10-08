<?php

namespace App\Notifications;

use App\Models\BloodRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BloodRequestNotification extends Notification
{
    use Queueable;

    protected $bloodRequest;
    protected $type;

    public function __construct(
        BloodRequest $bloodRequest,
        string $type = 'request_received'
    ) {
        $this->bloodRequest = $bloodRequest;
        $this->type = $type;
    }

    /**
     * Notification delivery channels
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Database notification
     */
    public function toDatabase(object $notifiable): array
    {
        $request = $this->bloodRequest;

        if ($this->type === 'request_received') {

            return [
                'title' => '🩸 New Blood Request',
                'message' => $request->patient_name
                    . ' needs '
                    . $request->blood_group
                    . ' blood in '
                    . $request->city
                    . '.',

                'type' => 'blood_request_received',

                'blood_request_id' => $request->id,

                'status' => $request->status,

                'urgency' => $request->urgency,

                'patient_name' => $request->patient_name,

                'blood_group' => $request->blood_group,

                'city' => $request->city,

                'hospital' => $request->hospital,
            ];
        }


        if ($this->type === 'request_accepted') {

            return [
                'title' => '✅ Blood Request Accepted',

                'message' => 'Your blood request has been accepted by the donor.',

                'type' => 'blood_request_accepted',

                'blood_request_id' => $request->id,

                'status' => $request->status,

                'donor_id' => $request->donor_id,
            ];
        }


        if ($this->type === 'request_rejected') {

            return [
                'title' => '❌ Blood Request Rejected',

                'message' => 'The donor has rejected your blood request.',

                'type' => 'blood_request_rejected',

                'blood_request_id' => $request->id,

                'status' => $request->status,

                'donor_id' => $request->donor_id,
            ];
        }


        if ($this->type === 'donation_completed') {

            return [
                'title' => '🎉 Donation Completed',

                'message' => 'The blood donation request has been completed successfully.',

                'type' => 'donation_completed',

                'blood_request_id' => $request->id,

                'status' => $request->status,

                'donor_id' => $request->donor_id,
            ];
        }


        return [
            'title' => '🩸 Blood Bank Update',

            'message' => 'There is an update regarding your blood request.',

            'type' => 'blood_request_update',

            'blood_request_id' => $request->id,

            'status' => $request->status,
        ];
    }
}