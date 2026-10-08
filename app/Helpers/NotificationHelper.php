<?php

namespace App\Helpers;

use App\Models\Notification;

class NotificationHelper
{
    /**
     * Create a notification for a user.
     */
    public static function send(
        int $userId,
        string $type,
        string $title,
        string $message,
        array $data = []
    ): Notification {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'is_read' => false,
        ]);
    }


    /**
     * Blood request notification.
     */
    public static function bloodRequest(
        int $donorUserId,
        int $bloodRequestId,
        int $requesterId,
        int $donorId,
        string $requesterName,
        string $bloodGroup,
        string $hospital,
        string $urgency
    ): Notification {
        return self::send(
            $donorUserId,
            'blood_request',
            '🩸 New Blood Request',
            $requesterName .
                ' needs ' .
                $bloodGroup .
                ' blood at ' .
                $hospital .
                '. Urgency: ' .
                ucfirst($urgency) .
                '.',
            [
                'blood_request_id' => $bloodRequestId,
                'requester_id' => $requesterId,
                'donor_id' => $donorId,
                'urgency' => $urgency,
            ]
        );
    }


    /**
     * Request accepted notification.
     */
    public static function accepted(
        int $userId,
        int $bloodRequestId,
        int $donorId,
        string $donorName,
        string $bloodGroup
    ): Notification {
        return self::send(
            $userId,
            'accepted',
            '✅ Blood Request Accepted',
            $donorName .
                ' has accepted your request for ' .
                $bloodGroup .
                ' blood.',
            [
                'blood_request_id' => $bloodRequestId,
                'donor_id' => $donorId,
            ]
        );
    }


    /**
     * Request rejected notification.
     */
    public static function rejected(
        int $userId,
        int $bloodRequestId,
        int $donorId,
        string $donorName,
        string $bloodGroup
    ): Notification {
        return self::send(
            $userId,
            'rejected',
            '❌ Blood Request Rejected',
            $donorName .
                ' has rejected your request for ' .
                $bloodGroup .
                ' blood.',
            [
                'blood_request_id' => $bloodRequestId,
                'donor_id' => $donorId,
            ]
        );
    }


    /**
     * Donation completed notification.
     */
    public static function completed(
        int $userId,
        int $bloodRequestId,
        int $donorId,
        string $bloodGroup
    ): Notification {
        return self::send(
            $userId,
            'completed',
            '🎉 Donation Completed',
            'The ' .
                $bloodGroup .
                ' blood donation request has been completed successfully.',
            [
                'blood_request_id' => $bloodRequestId,
                'donor_id' => $donorId,
            ]
        );
    }


    /**
     * Emergency blood request notification.
     */
    public static function emergency(
        int $donorUserId,
        int $bloodRequestId,
        int $requesterId,
        int $donorId,
        string $requesterName,
        string $bloodGroup,
        string $hospital,
        string $city
    ): Notification {
        return self::send(
            $donorUserId,
            'emergency',
            '🚨 Emergency Blood Request',
            'URGENT: ' .
                $requesterName .
                ' needs ' .
                $bloodGroup .
                ' blood in ' .
                $city .
                ' at ' .
                $hospital .
                '.',
            [
                'blood_request_id' => $bloodRequestId,
                'requester_id' => $requesterId,
                'donor_id' => $donorId,
                'city' => $city,
                'urgency' => 'critical',
            ]
        );
    }
}