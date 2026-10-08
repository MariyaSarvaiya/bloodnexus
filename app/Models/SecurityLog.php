<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SecurityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_id', 'user_id', 'event_type', 'severity', 'risk_score',
        'ip_address', 'user_agent', 'route', 'method', 'message', 'metadata', 'resolved_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'resolved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function incidentId(): string
    {
        return 'SEC-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
    }

    public static function record(array $data): self
    {
        $data['incident_id'] = $data['incident_id'] ?? self::incidentId();
        return static::create($data);
    }
}
