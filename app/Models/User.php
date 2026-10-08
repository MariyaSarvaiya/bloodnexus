<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements CanResetPasswordContract
{
    use HasFactory, Notifiable, CanResetPassword;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'blood_group',
        'city',
        'area',
        'password',
        'role',
        'is_suspended',
        'suspended_until',
        'failed_login_attempts',
        'locked_until',
        'security_blocked',
        'security_block_reason',
        'security_blocked_at',
        'last_login_warning_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_suspended' => 'boolean',
            'suspended_until' => 'datetime',
            'locked_until' => 'datetime',
            'failed_login_attempts' => 'integer',
            'security_blocked' => 'boolean',
            'security_blocked_at' => 'datetime',
            'last_login_warning_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | DONOR
    |--------------------------------------------------------------------------
    */

    public function donor()
    {
        return $this->hasOne(
            Donor::class,
            'user_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | BLOOD REQUESTS
    |--------------------------------------------------------------------------
    */

    public function securityLogs()
    {
        return $this->hasMany(SecurityLog::class);
    }

    public function bloodRequests()
    {
        return $this->hasMany(
            BloodRequest::class,
            'user_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SENT MESSAGES
    |--------------------------------------------------------------------------
    */

    public function sentMessages()
    {
        return $this->hasMany(
            Message::class,
            'sender_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RECEIVED MESSAGES
    |--------------------------------------------------------------------------
    */

    public function receivedMessages()
    {
        return $this->hasMany(
            Message::class,
            'receiver_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE HELPERS
    |--------------------------------------------------------------------------
    */

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function isDonor(): bool
    {
        return $this->role === 'donor';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSecurityBlocked(): bool
    {
        return (bool) $this->security_blocked;
    }

    public function accountIsLocked(): bool
    {
        if (!$this->locked_until) return false;
        if (now()->gte($this->locked_until)) {
            $this->forceFill(['locked_until'=>null,'failed_login_attempts'=>0])->saveQuietly();
            return false;
        }
        return true;
    }

    public function isSuspended(): bool
    {
        if (!(bool) $this->is_suspended) {
            return false;
        }

        if ($this->suspended_until && now()->gte($this->suspended_until)) {
            return false;
        }

        return true;
    }
}

