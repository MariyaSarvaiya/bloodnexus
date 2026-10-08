<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodInventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'blood_group', 'units', 'donor_id', 'blood_request_id',
        'transaction_at', 'source', 'note',
    ];

    protected $casts = [
        'units' => 'integer',
        'transaction_at' => 'datetime',
    ];

    public function donor() { return $this->belongsTo(Donor::class); }
    public function bloodRequest() { return $this->belongsTo(BloodRequest::class); }
}
