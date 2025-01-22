<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SmNotification extends Model
{
    use HasFactory;
    public static function notifications()
    {
        $user = Auth()->user();
        if ($user) {
            return $user->allNotifications->where('user_id', $user->id)->where('role_id', $user->role_id)->where('is_read', 0);
        }

    }
}
