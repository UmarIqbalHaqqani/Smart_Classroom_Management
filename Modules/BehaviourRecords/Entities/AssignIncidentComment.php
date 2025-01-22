<?php

namespace Modules\BehaviourRecords\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\BehaviourRecords\Entities\AssignIncident;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignIncidentComment extends Model
{
    use HasFactory;

    protected $fillable = [];

    // protected static function newFactory()
    // {
    //     return \Modules\BehaviourRecords\Database\factories\BehaviourCommentFactory::new();
    // }
    public function incident()
    {
        return $this->belongsTo(AssignIncident::class, 'incident_id', 'id')->withDefault();
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault();
    }
}
