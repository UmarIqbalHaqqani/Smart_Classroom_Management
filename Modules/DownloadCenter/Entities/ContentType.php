<?php

namespace Modules\DownloadCenter\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContentType extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    /* protected static function newFactory()
    {
        return \Modules\DownloadCenter\Database\factories\ContentTypeFactory::new();
    } */
}
