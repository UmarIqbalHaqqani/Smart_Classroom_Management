<?php
namespace App;

use App\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\StatusAcademicSchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmStudentIdCard extends Model
{
    use HasFactory;
    protected static function boot()
    {
        parent::boot();
  
        static::addGlobalScope(new StatusAcademicSchoolScope);
    }
    
    public static function roleName($id){
        $id_card= SmStudentIdCard::find($id);
        $arr=[];
        $roles=json_decode($id_card->role_id,true);        
        foreach($roles as $role){
            $arr[] = $role;
        }
        $roleNames = Role::whereIn('id',$arr)->get(['id','name']);           
        return $roleNames;
    }

    public function scopeStatus($query){
        return $query->where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id);
    }

    public static function studentName($parent_id){
        $studentInfos = SmStudent::where('parent_id',$parent_id)
                    ->where('active_status',1)
                    ->where('school_id', Auth::user()->school_id)
                    ->get(['full_name','student_photo','first_name','last_name']);
        return $studentInfos;
    }
}
