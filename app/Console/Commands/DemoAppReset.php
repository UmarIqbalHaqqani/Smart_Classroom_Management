<?php

namespace App\Console\Commands;

use App\InfixModuleManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class DemoAppReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset Database For Demo';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        date_default_timezone_set('Asia/Dhaka');
        if (!config('app.app_sync')) {
            return false;
        }else{
            Log::info('Reset Command run Start at ' . now()->toDateTimeString());
        }
        //stat resetting
        $this->startResetting();
        //reset database
        $this->migrateFresh();
        // seed data
        $this->dbSeed();

        //module enable for demo
        $this->moduleEnable();

        // install passport
        $this->passportInstall();

        // generate new key
        $this->generateNewKey();
        //end resetting
        $this->endResetting();
        //utitity
        $this->utitity();

        Log::info('Reset Command run finished at ' . now()->toDateTimeString());
    }

    public function startResetting()
    {
        Storage::put('.app_resetting', '');
    }

    public function endResetting()
    {
        Storage::delete('.app_resetting');
        Storage::put('.last_reset_time', now()->toDateTimeString());
    }

    protected function migrateFresh()
    {
        Artisan::call('db:wipe', array('--force' => true));
        Artisan::call('migrate', array('--force' => true));
    }

    protected function dbSeed()
    {
        Artisan::call('db:seed', array('--force' => true));
    }

    public function moduleEnable(){
        $modules = ['Zoom','Jitsi','MercadoPago','RazorPay','ParentRegistration','BehaviourRecords'];
        foreach($modules  as $module){
            $exist = InfixModuleManager::where('name',$module)->first();
            if($exist){
                $exist->purchase_code = time(); 
                $exist->save();
            }
        }
    }

    protected function passportInstall()
    {
        Artisan::call('migrate', [
            '--path' => 'vendor/laravel/passport/database/migrations',
            '--force' => true,

        ]);
        Artisan::call('passport:install');
    }

    protected function generateNewKey()
    {
        Artisan::call('key:generate', ['--force' => true]);
    }

    // public function resetCustomCssJsFiles()
    // {
    //     $css_path = 'public/frontend/infixlmstheme/css/custom.css';
    //     $js_path = 'public/frontend/infixlmstheme/js/custom.js';
    //     File::put($css_path, "");
    //     File::put($js_path, "");
    // }

    
    public function utitity()
    {
        Artisan::call('optimize:clear');
        File::delete(File::glob('bootstrap/cache/*.php'));
        File::delete(File::glob('storage/framework/laravel-excel/*'));
        File::delete(File::glob('storage/framework/sessions/*'));
        // $directory = config('session.files');
        // $ignoreFiles = ['.gitignore', '.', '..'];
        // $files = scandir($directory);
        // foreach ( $files as $file ) {
        //     if( !in_array($file,$ignoreFiles) ) {
        //         unlink($directory . '/' . $file);
        //     }
        // }

        array_map('unlink', array_filter((array)glob(storage_path('logs/*.log'))));
        array_map('unlink', array_filter((array)glob(storage_path('debugbar/*.json'))));

        envu([
            'APP_DEBUG' => "false",
            'FORCE_HTTPS' => "false",
        ]);

    }
}
