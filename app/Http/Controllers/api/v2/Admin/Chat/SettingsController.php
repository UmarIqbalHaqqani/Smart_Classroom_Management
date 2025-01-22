<?php

namespace App\Http\Controllers\api\v2\Admin\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Pusher\Pusher;

class SettingsController extends Controller
{
    public function pusherAuth(Request $request){
        \Log::info('PusherAuthController', $request->toArray());
        
        $pusher = new Pusher(
            app('general_settings')->get('pusher_app_key'),
            app('general_settings')->get('pusher_app_secret'),
            app('general_settings')->get('pusher_app_id')
        );

        $channel     = request('channel_name');
        $socket_id   = request('socket_id');

        return $pusher->socket_auth($channel, $socket_id);
    }
}
