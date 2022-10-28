<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $table = 'vehicle';

    protected $fillable = [
        'id_vehicle',
        'vehicle_type',
        'color',
        'year_production',
        'price',
        'machine',
        'suspension',
        'transmition',
        'capacity',
        'type',
    ];

}
