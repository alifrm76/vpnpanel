<?php

namespace App\Filament\Resources\ServerResource\Pages;

use App\Filament\Resources\ServerResource;
use Filament\Resources\Pages\ListRecords;
use TomatoPHP\FilamentApi\Traits\InteractWithAPI;   // ←

class ListServers extends ListRecords
{
    use InteractWithAPI;                            // ←
    protected static string $resource = ServerResource::class;
}
