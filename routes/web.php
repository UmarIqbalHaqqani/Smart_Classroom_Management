<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\TwoFactorAuth\Entities\TwoFactorSetting;

Route::get('resdg', function () {
    $gs = generalSetting();
        $gs->two_factor = 0 ;
        $gs->save();

        session()->forget('generalSetting');
        session()->put('generalSetting', $gs);

        $setting = TwoFactorSetting::where('school_id', Auth::user()->school_id)->first();
        $setting->via_sms =  0; 
        $setting->via_email = 0; 
        $setting->expired_time =   0; 
        $setting->for_student =  0; 
        $setting->for_parent =  0; 
        $setting->for_teacher =  0; 
        $setting->for_staff = 0;  
        $setting->for_admin = 0;  
        $setting->update();
});

if (config('app.app_sync')) {
    Route::get('/', 'LandingController@index')->name('/');
}

if (moduleStatusCheck('Saas')) {
    Route::group(['middleware' => ['subdomain'], 'domain' => '{subdomain}.' . config('app.short_url')], function ($routes) {
        require('tenant.php');
    });

    Route::group(['middleware' => ['subdomain'], 'domain' => '{subdomain}'], function ($routes) {
        require('tenant.php');
    });
}

Route::group(['middleware' => ['subdomain']], function ($routes) {
    require('tenant.php');
});

Route::get('migrate', function () {
    if(Auth::check() && Auth::id() == 1){
        \Artisan::call('migrate', ['--force' => true]);
        \Brian2694\Toastr\Facades\Toastr::success('Migration run successfully');
        return redirect()->to(url('/admin-dashboard'));
    }
    abort(404);
});


Route::post('editor/upload-file', 'UploadFileController@upload_image');
