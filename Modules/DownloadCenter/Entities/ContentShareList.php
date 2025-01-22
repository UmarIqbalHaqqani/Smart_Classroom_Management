<?php

namespace Modules\DownloadCenter\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContentShareList extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $casts = [
        'content_ids' => 'array',
        'gr_role_ids' => 'array',
        'ind_user_ids' => 'array',
        'section_ids' => 'array'
    ];
    public function user()
    {
        return $this->belongsTo('App\User', 'shared_by', 'id');
    }
}
