<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    // protected $router;
    // public function __construct()
    // {
    //      $router = RouteService();
    // }
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';
    protected $apiNamespace = 'App\Http\Controllers\api\v2';
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapV2ApiRoutes();
        $this->mapWebRoutes();
        $this->mapAdminRoutes();
        $this->mapStudentRoutes();
        $this->mapParentRoutes();
        $this->mapTeacherRoutes();
        $this->mapConfigureRoutes();
        $this->mapPageBuilderRoutes();
        $this->mapGraduateRoutes();
    }

    
     
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }
    protected function mapAdminRoutes()
    {
        Route::middleware(['web','2fa'])
             ->namespace($this->namespace)
             ->group(base_path('routes/admin.php'));
    }
    protected function mapStudentRoutes()
    {
        Route::middleware(['web','2fa','fees_due_check'])
             ->namespace($this->namespace)
             ->group(base_path('routes/student.php'));
    }
    protected function mapParentRoutes()
    {
        Route::middleware(['web','2fa','fees_due_check'])
             ->namespace($this->namespace)
             ->group(base_path('routes/parent.php'));
    }
    protected function mapTeacherRoutes()
    {
        Route::middleware(['web','2fa'])
             ->namespace($this->namespace)
             ->group(base_path('routes/teacher.php'));
    }

    protected function mapGraduateRoutes()
    {
        Route::middleware(['web','2fa'])
             ->namespace($this->namespace)
             ->group(base_path('routes/graduate.php'));
    }

    protected function mapPageBuilderRoutes()
    {
        Route::middleware(['web'])->group(base_path('routes/pagebuilder.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    protected function mapV2ApiRoutes(){
        Route::prefix('api/v2')
        ->middleware('api')
        ->namespace($this->apiNamespace)
        ->group(base_path('routes/v2api.php'));
    }

    // configuration route

    protected function mapConfigureRoutes()
    {
        Route::namespace($this->namespace)
             ->group(base_path('routes/configuration.php'));
    }
}
