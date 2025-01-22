<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmExpertTeacher extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function staff()
    {
        return $this->belongsTo('App\SmStaff', 'staff_id', 'id')->withDefault();
    }
}
