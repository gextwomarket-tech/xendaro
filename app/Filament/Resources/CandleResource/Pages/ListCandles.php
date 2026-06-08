<?php

namespace App\Filament\Resources\CandleResource\Pages;

use App\Filament\Resources\CandleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCandles extends ListRecords
{
    protected static string $resource = CandleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
