<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'gas_level',
        'suhu',
        'api_detected',
        'apar_status',
    ];

    protected $casts = [
        'api_detected' => 'boolean',
        'suhu' => 'float',
        'gas_level' => 'integer',
    ];
}
