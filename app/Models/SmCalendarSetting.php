<?php

namespace App\Models;

use App\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmCalendarSetting extends Model
{
    use HasFactory;
    protected static function boot()
    {
        parent::boot();
  
        return static::addGlobalScope(new SchoolScope);
    }
}
