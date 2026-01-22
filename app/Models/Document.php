<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    protected $fillable = [
        'external_id', 
        'case_id', 
        'filename', 
        'mime_type', 
        'category', 
        'pages', 
        'uploaded_by'
    ];

    // Attiecība ar muitas lietu
    public function customsCase()
    {
        return $this->belongsTo(CustomsCase::class, 'case_id', 'external_id');
    }

    // Attiecība ar augšupielādētāju (lietotāju)
    public function uploader()
    {
        // Pieņemam, ka uploaded_by glabā lietotāja external_id vai parasto ID
        return $this->belongsTo(User::class, 'uploaded_by', 'external_id');
    }

    /* --- Helperi, kas atvieglo dzīvi Blade skatos --- */

    // Atgriež pilnu URL failam (lai var uztaisīt linku)
    public function getUrlAttribute()
    {
        return Storage::url('documents/' . $this->filename);
    }

    // Pārbauda, vai tas ir attēls (noder, lai rādītu preview)
    public function isImage()
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    // Atgriež smuku kategorijas nosaukumu (ja DB glabājas kodi)
    public function getCategoryLabelAttribute()
    {
        return match($this->category) {
            'invoice' => 'Rēķins',
            'cmr'     => 'CMR pavadzīme',
            'packing' => 'Iepakojuma lapa',
            default   => ucfirst($this->category),
        };
    }
}