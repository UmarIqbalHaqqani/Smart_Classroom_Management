<?php

namespace App;

use App\Scopes\ActiveStatusSchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SmDormitoryList extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'dormitory_name' => 'string',
    ];

    
    protected static function boot()
    {
        parent::boot();
  
        static::addGlobalScope(new ActiveStatusSchoolScope);
    } 
    
}
