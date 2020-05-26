<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use App\TokenStore\TokenCache;
use Illuminate\Support\Facades\Storage;

class UserProfileServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('profile', function ($view) 
        {   
            if (session('image')){
                $image = base64_encode(Storage::get('userimage/'. session('seqtaId') . '.jpg'));
            } else {
                $image = false;
            }
            $view->with('image', $image);
        });
    }
}
