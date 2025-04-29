<?php

     namespace App\Providers;

     use Filament\Panel;
     use Filament\Support\Assets\AlpineComponent;
     use Filament\Support\Assets\Asset;
     use Filament\Support\Assets\Css;
     use Filament\Support\Assets\Js;
     use Filament\Support\Colors\Color;
     use Filament\Support\Facades\FilamentAsset;
     use Filament\Support\Facades\FilamentColor;
     use Illuminate\Support\ServiceProvider;

     class FilamentServiceProvider extends ServiceProvider
     {
         public function boot(): void
         {
             FilamentAsset::register([
                 Css::make('custom-styles', __DIR__ . '/../../resources/css/custom.css'),
             ]);

             FilamentColor::register([
                 'primary' => Color::Blue,
             ]);
         }
     }