<?php

namespace App\Filament\Resources\EconomicEventResource\Pages;

use App\Filament\Resources\EconomicEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEconomicEvents extends ListRecords
{
    protected static string $resource = EconomicEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
