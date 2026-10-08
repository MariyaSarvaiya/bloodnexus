<?php

namespace App\Support;

/**
 * Blood-group compatibility helper for red-cell donation matching.
 * Keys are donor groups; values are recipient groups the donor can support.
 */
class BloodCompatibility
{
    private const MAP = [
        'O-'  => ['O-', 'O+', 'A-', 'A+', 'B-', 'B+', 'AB-', 'AB+'],
        'O+'  => ['O+', 'A+', 'B+', 'AB+'],
        'A-'  => ['A-', 'A+', 'AB-', 'AB+'],
        'A+'  => ['A+', 'AB+'],
        'B-'  => ['B-', 'B+', 'AB-', 'AB+'],
        'B+'  => ['B+', 'AB+'],
        'AB-' => ['AB-', 'AB+'],
        'AB+' => ['AB+'],
    ];

    public static function recipientGroupsForDonor(string $donorGroup): array
    {
        $group = strtoupper(trim($donorGroup));
        return self::MAP[$group] ?? [$group];
    }

    public static function canDonateTo(string $donorGroup, string $recipientGroup): bool
    {
        return in_array(
            strtoupper(trim($recipientGroup)),
            self::recipientGroupsForDonor($donorGroup),
            true
        );
    }

    public static function donorGroupsForRecipient(string $recipientGroup): array
    {
        $recipient = strtoupper(trim($recipientGroup));
        $groups = [];
        foreach (array_keys(self::MAP) as $donorGroup) {
            if (self::canDonateTo($donorGroup, $recipient)) {
                $groups[] = $donorGroup;
            }
        }
        return $groups;
    }
}
