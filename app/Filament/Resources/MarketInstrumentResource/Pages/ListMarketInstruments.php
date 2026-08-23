<?php

namespace App\Filament\Resources\MarketInstrumentResource\Pages;

use App\Filament\Resources\MarketInstrumentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMarketInstruments extends ListRecords
{
    protected static string $resource = MarketInstrumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
