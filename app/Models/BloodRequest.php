<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'donor_id',
        'patient_name',
        'requester_type',
        'request_for',
        'blood_group',
        'city',
        'area',
        'hospital',

        'contact',
        'contact_phone',

        'units',
        'urgency',
        'emergency_mode',
        'message',
        'reason',
        'donation_date',
        'donation_time',
        'completed_at',
        'status',
        'parent_request_id',
        'units_fulfilled',
        'fulfilled_at',
        'closure_reason',
    ];

    protected $casts = [
        'units' => 'integer',
        'units_fulfilled' => 'integer',
        'emergency_mode' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'donation_date' => 'date',
        'completed_at' => 'datetime',
        'fulfilled_at' => 'datetime',
    ];


    /*
    |--------------------------------------------------------------------------
    | USER / BLOOD SEEKER
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DONOR
    |--------------------------------------------------------------------------
    */

    public function donor()
    {
        return $this->belongsTo(
            Donor::class,
            'donor_id'
        );
    }



    public function parentRequest()
    {
        return $this->belongsTo(self::class, 'parent_request_id');
    }

    public function donorAssignments()
    {
        return $this->hasMany(self::class, 'parent_request_id');
    }

    public function isMasterRequest(): bool
    {
        return $this->parent_request_id === null;
    }

    public function remainingUnits(): int
    {
        return max(0, (int) $this->units - (int) $this->units_fulfilled);
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS HELPERS
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isMatched(): bool
    {
        return $this->status === 'matched';
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}