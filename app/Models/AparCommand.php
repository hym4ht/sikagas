<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AparCommand extends Model
{
    protected $table = 'apar_commands';

    protected $fillable = [
        'command',
        'source',
    ];
}
