<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    // $this->app['auth']->viaRequest('api', function ($request) {
    //     if ($request->input('api_token')) {
    //         return User::where('api_token', $request->input('api_token'))->first();
    //     }
    // });
    /**
     * Register any application services.
     *
     * @return void
     */
    //mengatur cara mencari user berdasarkan token.
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['auth']->viaRequest('api', function ($request) {
            $token = $request->bearerToken(); 
            if ($token) {
                return User::where('api_token', hash('sha256', $token))->first();
            }
        });
    }
}
