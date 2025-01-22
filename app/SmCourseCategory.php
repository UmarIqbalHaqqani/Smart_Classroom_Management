<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmCourseCategory extends Model
{
    protected $guarded = ['id'];
    use HasFactory;
    public function courses()
    {
        return $this->hasMany('App\SmCourse', 'category_id');
    }
}
