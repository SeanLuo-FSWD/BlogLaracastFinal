<?php

namespace App\Providers;

use App\Services\MailchimpNewsletter;
use App\Services\Newsletter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use MailchimpMarketing\ApiClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //@@61 This instructs Laravel on how to inject the Newsletter class when the "magic" binding cannot infer the class. Used on NewsletterController.php via Newsletter.php
        app()->bind(Newsletter::class, function() {
            $client = (new ApiClient)->setConfig([
                'apiKey' => config('services.mailchimp.key'),
                'server' => 'us20'
            ]);

            //@@61 You pass in the interface Newsletter above, but specify the type by returning MailchimpNewsletter
           return new MailchimpNewsletter(
               $client
           );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //@@56_08:01 Used to unguard[] all models so you don't have to do it individually.
        Model::unguard();

        //@@69 authenticated user passed in automatically by Laravel
        Gate::define('admin', function (User $user) {
            return $user->username === 'bob@bob.com';
        });

        //69_07:24 custom directive "admin" to be used in layout.blade.php
        Blade::if('admin', function () {
            //@@69_03:30 Means if admin, then user CAN do the specified action. Returns a boolean.
            return request()->user()?->can('admin');
                //@@69_08:45 "?" Means if user, proceed, otherwise return false right away.
        });
    }
}
