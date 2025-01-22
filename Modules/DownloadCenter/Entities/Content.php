<?php

namespace Modules\DownloadCenter\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\DownloadCenter\Entities\ContentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Content extends Model
{
    use HasFactory;

    protected $fillable = [];

    public function contentType()
    {
        return $this->belongsTo(ContentType::class, 'content_type_id', 'id')->withDefault();
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'uploaded_by', 'id')->withDefault();
    }
}
