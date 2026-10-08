<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Donor extends Model
{
    use HasFactory;


    // ==========================================================
    // MASS ASSIGNMENT
    // ==========================================================

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'blood_group',
        'city',
        'area',
        'address',
        'medical_history',
        'last_donation_date',
        'is_available',
    ];


    // ==========================================================
    // CASTS
    // ==========================================================

    protected $casts = [
        'is_available' => 'boolean',
        'last_donation_date' => 'date',
    ];


    // ==========================================================
    // USER ACCOUNT
    // ==========================================================

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    // ==========================================================
    // BLOOD REQUESTS RECEIVED BY THIS DONOR
    // ==========================================================

    public function bloodRequests()
    {
        return $this->hasMany(BloodRequest::class);
    }


    // ==========================================================
    // PENDING REQUESTS
    // ==========================================================

    public function pendingRequests()
    {
        return $this->hasMany(BloodRequest::class)
            ->where('status', 'pending');
    }


    // ==========================================================
    // ACCEPTED REQUESTS
    // ==========================================================

    public function acceptedRequests()
    {
        return $this->hasMany(BloodRequest::class)
            ->where('status', 'accepted');
    }


    // ==========================================================
    // CHECK 3 MONTH DONATION COOLDOWN
    // ==========================================================

    public function isInDonationCooldown(): bool
    {
        /*
        |----------------------------------------------------------
        | No previous donation
        |----------------------------------------------------------
        */

        if (!$this->last_donation_date) {
            return false;
        }


        /*
        |----------------------------------------------------------
        | Next eligible date = last donation + 3 months
        |----------------------------------------------------------
        */

        $nextDonationDate = Carbon::parse(
            $this->last_donation_date
        )->addMonthsNoOverflow(3);


        return now()->startOfDay()->lt(
            $nextDonationDate->startOfDay()
        );
    }


    // ==========================================================
    // NEXT ELIGIBLE DONATION DATE
    // ==========================================================

    public function nextDonationDate(): ?Carbon
    {
        if (!$this->last_donation_date) {
            return null;
        }


        return Carbon::parse(
            $this->last_donation_date
        )->addMonthsNoOverflow(3);
    }


    // ==========================================================
    // DAYS REMAINING FOR NEXT DONATION
    // ==========================================================

    public function donationCooldownDays(): int
    {
        $nextDate = $this->nextDonationDate();


        if (!$nextDate) {
            return 0;
        }


        if (now()->startOfDay()->gte(
            $nextDate->startOfDay()
        )) {
            return 0;
        }


        return now()
            ->startOfDay()
            ->diffInDays(
                $nextDate->startOfDay()
            );
    }


    // ==========================================================
    // ELIGIBILITY HELPER
    // ==========================================================

    public function canDonate(): bool
    {
        return !$this->isInDonationCooldown();
    }


    // ==========================================================
    // AVAILABILITY HELPER
    // ==========================================================

    public function isAvailable(): bool
    {
        /*
        |----------------------------------------------------------
        | Cooldown always overrides availability
        |----------------------------------------------------------
        */

        if ($this->isInDonationCooldown()) {
            return false;
        }


        return (bool) $this->is_available;
    }


    // ==========================================================
    // BLOOD GROUP MATCH
    // ==========================================================

    public function matchesBloodGroup(string $bloodGroup): bool
    {
        return strtoupper(
            trim($this->blood_group)
        ) === strtoupper(
            trim($bloodGroup)
        );
    }
}