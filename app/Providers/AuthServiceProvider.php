<?php

     namespace App\Providers;

     use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
     use App\Models\Server;
     use App\Policies\ServerPolicy;

     class AuthServiceProvider extends ServiceProvider
     {
         protected $policies = [
             Server::class => ServerPolicy::class,
         ];

         public function boot(): void
         {
             $this->registerPolicies();
         }
     }