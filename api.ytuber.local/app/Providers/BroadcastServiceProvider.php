<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;


class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    protected $listen = [
        PrivateChat::class =>[
            SendMessageMainChat::class
        ]
    ];

    public function boot()
    {
        //Broadcast::routes(['middleware' => ['auth:sanctum']]);
        //Broadcast::routes();
        //Broadcast::routes(['middleware' => 'auth:api']);
        Broadcast::routes(["prefix" => "api", "middleware" => ["auth:sanctum"]]);
        //Broadcast::routes(["prefix" => "api", 'middleware' => ['web']]);
        require base_path('routes/channels.php');
    }
}
