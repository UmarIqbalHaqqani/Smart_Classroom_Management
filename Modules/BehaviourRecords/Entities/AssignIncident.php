<?php

namespace Modules\BehaviourRecords\Entities;

use App\Models\User;
use App\SmAcademicYear;
use App\Models\StudentRecord;
use Illuminate\Database\Eloquent\Model;
use Modules\BehaviourRecords\Entities\Incident;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignIncident extends Model
{
    use HasFactory;

    protected $fillable = ['point'];

    // protected static function newFactory()
    // {
    //     return \Modules\BehaviourRecords\Database\factories\AssignIncidentFactory::new();
    // }
    public function incident()
    {
        return $this->belongsTo(Incident::class, 'incident_id', 'id')->withDefault();
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'added_by', 'id')->withDefault();
    }
    public function academicYear()
    {
        return $this->belongsTo(SmAcademicYear::class, 'academic_id', 'id')->withDefault();
    }
    public function studentRecord()
    {
        return $this->belongsTo(StudentRecord::class, 'record_id');
    }
}
