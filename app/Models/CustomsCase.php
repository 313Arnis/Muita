<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomsCase extends Model
{
    // Datubāzes tabulas nosaukums
    protected $table = 'cases';

    protected $fillable = [
        'external_id', 'external_ref', 'status', 'priority', 
        'arrival_ts', 'checkpoint_id', 'origin_country', 
        'destination_country', 'risk_flags', 'declarant_id', 
        'consignee_id', 'vehicle_id', 'decision_notes', 
        'decision_made_at', 'decision_made_by'
    ];

    // Automātiski pārveidojam JSON kolonnu par masīvu un datumus par Carbon objektiem
    protected $casts = [
        'risk_flags' => 'array',
        'arrival_ts' => 'datetime',
        'decision_made_at' => 'datetime',
    ];

    /* --- Attiecības (Relationships) --- */

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'external_id');
    }

    public function declarant()
    {
        return $this->belongsTo(Party::class, 'declarant_id', 'external_id');
    }

    public function consignee()
    {
        return $this->belongsTo(Party::class, 'consignee_id', 'external_id');
    }

    public function inspections()
    {
        return $this->hasMany(Inspection::class, 'case_id', 'external_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'case_id', 'external_id');
    }

    // Pievienoju klāt arī lēmuma pieņēmēju, ja nu noder statistikā
    public function decisionMaker()
    {
        return $this->belongsTo(User::class, 'decision_made_by');
    }
    // App/Models/RiskCase.php
// App/Models/CustomsCase.php

public function getRiskInfoAttribute() {
    // Tā kā modelī ir casts => array, Laravel šeit jau iedod masīvu.
    // json_decode vairs NAV vajadzīgs.
    $flags = $this->risk_flags ?? []; 
    
    $count = count($flags);
    
    if (in_array('HIGH_RISK', $flags) || $count > 2) {
        return (object)['label' => 'Augsts', 'class' => 'danger', 'level' => 'high'];
    }
    
    return $count == 2 
        ? (object)['label' => 'Vidējs', 'class' => 'warning', 'level' => 'medium']
        : (object)['label' => 'Zems', 'class' => 'info', 'level' => 'low'];
}
}