<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = ['external_id', 'plate_no', 'country', 'make', 'model', 'vin'];

    public function cases()
    {
        return $this->hasMany(CustomsCase::class, 'vehicle_id', 'external_id');
    }
}