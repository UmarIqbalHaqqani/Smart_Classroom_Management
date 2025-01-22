<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmStaffAttendence extends Model
{
    use HasFactory;
    protected $table = "sm_staff_attendences";

    protected $guarded = [];

    public function StaffInfo()
    {
        return $this->belongsTo('App\SmStaff', 'staff_id', 'id');
    }
}
