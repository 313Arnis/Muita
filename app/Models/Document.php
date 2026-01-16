<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'external_id', 'case_id', 'filename', 
        'mime_type', 'category', 'pages', 'uploaded_by'
    ];

    public function customsCase()
    {
        return $this->belongsTo(CustomsCase::class, 'case_id', 'external_id');
    }
}