<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use App\Models\User;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            $this->mapApiRoutes();

            $this->mapWebRoutes();

            require base_path('routes/scim.php');
        });

        /**
         * Add default searching of user by nip_baru.
         * besides of id as the default parameter
         * @author [Y. Rihan] [yosral@bps.go.id>]
         * @since [v6.0.9]
         * @return [user's model by id or nip_baru]
         */
        Route::bind('user', function ($value) {
            return User::where('id', $value)->orWhere('nip_baru', $value)->orWhere('employee_num', $value)->firstOrFail();
        });
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::group([
            'middleware' => 'web',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/web/hardware.php');
            require base_path('routes/web/models.php');
            require base_path('routes/web/accessories.php');
            require base_path('routes/web/licenses.php');
            require base_path('routes/web/consumables.php');
            require base_path('routes/web/consumablesdetails.php');
            require base_path('routes/web/consumablestransaction.php');
            require base_path('routes/web/fields.php');
            require base_path('routes/web/components.php');
            require base_path('routes/web/users.php');
            require base_path('routes/web/kits.php');
            require base_path('routes/web.php');
        });
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
        Route::group([
            'middleware' => 'auth:api',
            'namespace' => $this->namespace,
            'prefix' => 'api',
        ], function ($router) {
            require base_path('routes/api.php');
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * https://laravel.com/docs/8.x/routing#rate-limiting
     *
     * @return void
     */
    protected function configureRateLimiting()
    {

        // Rate limiter for API calls
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(config('app.api_throttle_per_minute'))->by(optional($request->user())->id ?: $request->ip());
        });

        // Rate limiter for forgotten password requests
        RateLimiter::for('forgotten_password', function (Request $request) {
            return Limit::perMinute(config('auth.password_reset.max_attempts_per_min'))->by(optional($request->user())->id ?: $request->ip());
        });

    }
}
