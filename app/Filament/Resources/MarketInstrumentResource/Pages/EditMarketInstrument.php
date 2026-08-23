<?php

namespace App\Filament\Resources\MarketInstrumentResource\Pages;

use App\Filament\Resources\MarketInstrumentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMarketInstrument extends EditRecord
{
    protected static string $resource = MarketInstrumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
