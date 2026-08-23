<?php

namespace App\Filament\Resources\TradeHistoryResource\Pages;

use App\Filament\Resources\TradeHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTradeHistories extends ListRecords
{
    protected static string $resource = TradeHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
