<?php

namespace App;

use App\Models\SmNewsComment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmNews extends Model
{
    use HasFactory;
    public function category()
    {
        return $this->belongsTo('App\SmNewsCategory');
    }

    public function newsComments()
    {
        return $this->hasMany(SmNewsComment::class, 'news_id')->whereNull('parent_id')->where('status', 1);
    }
    

    public function scopeMissions($q)
    {
        return $q->whereHas('category', function($q){
            
            return $q->where('type', 'mission');
         
        });
    }

    public function scopeHistories($q)
    {
        return $q->whereHas('category', function($q){
            
            return $q->where('type', 'history');
         
        });
    }
}
