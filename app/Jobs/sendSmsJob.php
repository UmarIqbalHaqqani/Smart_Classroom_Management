<?php

namespace App\Jobs;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CommunicateNotification;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Http\Controllers\Admin\SystemSettings\SmSystemSettingController;

class sendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $sms;
    protected $title;
    protected $user;
    protected $numbers=[];

    public function __construct($sms,$title,$numbers,$user)
    {
        $this->sms = $sms;
        $this->title = $title;
        $this->numbers = $numbers;
        $this->user = $user;
    }

    public function handle()
    {
        
        try {
            foreach ($this->numbers as $key => $number) {
                $notification_data=[];
                $notification_data['title']=$this->title;
                $notification_data['body']=$this->sms;
                $notification_data['phone_number']=$number;
                $notification_data['deviceID']=$this->user->device_token;
                #Notification::send($this->user, new CommunicateNotification($notification_data));
                $systemSettingController = new SmSystemSettingController();
                $systemSettingController->flutterNotificationSmsApi(new Request($notification_data));
            }
        }catch (\Exception $e) {
            Log::info($e->getMessage());
        }
        
    }
}
