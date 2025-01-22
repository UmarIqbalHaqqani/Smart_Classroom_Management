<?php

namespace App;

use App\Scopes\SchoolScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmParent extends Model
{
    use HasFactory;
    protected $casts = [
        'id'                    => 'integer',
        'fathers_name'          => 'string',
        'fathers_mobile'        => 'string',
        'fathers_occupation'    => 'string',
        'fathers_photo'         => 'string',
        'mothers_name'          => 'string',
        'mothers_mobile'        => 'string',
        'mothers_occupation'    => 'string',
        'mothers_photo'         => 'string',
        'guardians_name'        => 'string',
        'guardians_mobile'      => 'string',
        'guardians_email'       => 'string',
        'guardians_occupation'  => 'string',
        'guardians_relation'    => 'string',
        'role_id' => 'integer',
        'active_status' => 'integer',
        'user_id' => 'integer',
        'school_id' => 'integer',
        'academic_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

	protected static function boot (){
        parent::boot();
        static::addGlobalScope(new SchoolScope);
    }
    
    public function parent_user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function childrens()
    {
        return $this->hasMany('App\SmStudent', 'parent_id', 'id')->where('active_status', 1);
    }

    public static function myChildrens()
    {

        try {
            // if (Session::get('childrens')) {
            //     return Session::get('childrens');
            // } else {
                $parent = Auth::user()->parent;
                $childrens = $parent->childrens;
                Session::put('childrens', $childrens);
                return Session::get('childrens');
            // }

        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }
}
