<?php

namespace Modules\BehaviourRecords\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BehaviourRecordSetting extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    // protected static function newFactory()
    // {
    //     return \Modules\BehaviourRecords\Database\factories\BehaviourRecordSettingFactory::new();
    // }
}
