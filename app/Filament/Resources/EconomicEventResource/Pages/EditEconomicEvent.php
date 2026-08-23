<?php

namespace App\Filament\Resources\EconomicEventResource\Pages;

use App\Filament\Resources\EconomicEventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEconomicEvent extends EditRecord
{
    protected static string $resource = EconomicEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
