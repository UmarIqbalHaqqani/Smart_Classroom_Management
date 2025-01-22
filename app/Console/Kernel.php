<?php

namespace App\Console;

use Modules\Lead\Console\ReminderLead;
use Illuminate\Support\Facades\Storage;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Events\Dispatcher;
use Modules\University\Console\PaymentReminder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\QrcodeAttendnceAbsenseCommand;
class Kernel extends ConsoleKernel
{
    public function __construct(Application $app, Dispatcher $events)
    {
        parent::__construct($app, $events);
        // if (moduleStatusCheck("Lead")) {
        //     $this->commands = array_merge($this->commands, [ReminderLead::class]);
        // }
        // if (moduleStatusCheck("University")) {
        //     $this->commands = array_merge($this->commands, [PaymentReminder::class]);
        // }

    }

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */

    protected $commands = [
        Commands\DemoCron::class,
        QrcodeAttendnceAbsenseCommand::class,
    ];


    protected $PurchaseVerificationMiddleware = [
        'purchaseVerification' => \App\Http\Middleware\PurchaseVerification::class,
    ];

    //    protected $middleware = [
    //     \App\http\Middleware\CustomerMiddleware::class,
    // ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        if (config('app.app_sync') && Storage::exists('.app_resetting')) {
            $schedule->command('app:reset')->everySixHours()->withoutOverlapping();
        }else{
            $schedule->command('absent_notification:sms')->everyMinute();
            $schedule->command('queue:work')->everyMinute()->withoutOverlapping();
            if (moduleStatusCheck("Lead") == true) {
                $schedule->command('lead:reminder')->everyTenMinutes()->withoutOverlapping();
            }
    
            if (moduleStatusCheck("University") == true) {
                $schedule->command('payment:reminder')->everyTenMinutes()->withoutOverlapping();
            }
            if (moduleStatusCheck("QRCodeAttendance") == true) {
                $schedule->command('qrcode:attendance')->everyOddHour()->withoutOverlapping();
            }
           
        }
    }

    /**
     * Register the commands for the application.

     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
