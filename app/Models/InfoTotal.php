<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfoTotal extends Model
{
    protected $table = 'info_totals';

    protected $fillable = [
        'spec_version', 'exported_at', 'vehicles', 
        'parties', 'users', 'cases', 'inspections', 'documents'
    ];

    protected $casts = [
        'exported_at' => 'datetime'
    ];
}