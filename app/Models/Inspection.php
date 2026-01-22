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
        'assigned_to',
        'decision',
        'decision_by',
        'decision_notes',
        'decision_ts'
    ];

    protected $casts = [
        'checks' => 'array',
        'start_ts' => 'datetime'
    ];

    public function inspector()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'username');
    }

    public function customsCase()
    {
        return $this->belongsTo(CustomsCase::class, 'case_id', 'external_id');
    }
}
