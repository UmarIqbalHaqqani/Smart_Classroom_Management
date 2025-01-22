<?php

namespace Modules\BehaviourRecords\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\BehaviourRecords\Entities\AssignIncident;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = [];

    // protected static function newFactory()
    // {
    //     return \Modules\BehaviourRecords\Database\factories\IncidentFactory::new();
    // }
    public function incidents()
    {
        return $this->hasMany(AssignIncident::class, 'incident_id');
    }
}
