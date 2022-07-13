<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        if ($request->server->has('HTTP_X_ORIGINAL_HOST')) {
            $this->app['url']->forceRootUrl($request->server->get('HTTP_X_FORWARDED_PROTO').'://'.$request->server->get('HTTP_X_ORIGINAL_HOST'));
            $this->app['url']->forceScheme('https');
        }
    }
}
