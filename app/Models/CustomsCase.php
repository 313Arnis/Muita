<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomsCase extends Model
{
    protected $table = 'cases';

    protected $fillable = [
        'external_id', 'external_ref', 'status', 'priority', 
        'arrival_ts', 'checkpoint_id', 'origin_country', 
        'destination_country', 'risk_flags', 'declarant_id', 
        'consignee_id', 'vehicle_id'
    ];

   
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'external_id');
    }

        public function declarant(): BelongsTo
    {
        return $this->belongsTo(Party::class, 'declarant_id', 'external_id');
    }

   
    public function consignee(): BelongsTo
    {
        return $this->belongsTo(Party::class, 'consignee_id', 'external_id');
    }

   
    public function inspections(): HasMany
    {
        return $this->hasMany(Inspection::class, 'case_id', 'external_id');
    }

    
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'case_id', 'external_id');
    }
}