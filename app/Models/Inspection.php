<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    protected $fillable = [
        'external_id',
        'case_id',
        'type',
        'requested_by',
        'start_ts',
        'location',
        'checks',
        'assigned_to'
    ];

    protected $casts = [
        'checks' => 'array',
        'start_ts' => 'datetime'
    ];

    public function customsCase()
    {
        return $this->belongsTo(CustomsCase::class, 'case_id', 'external_id');
    }
}
