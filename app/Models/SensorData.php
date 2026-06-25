<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    protected $table = 'sensor_data';

    protected $fillable = [
        'gas_value',
        'gas_ppm',
        'status',
        'apar_aktif',
        'buzzer_aktif',
    ];
}
