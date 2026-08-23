<?php

namespace App\Filament\Resources\TradeHistoryResource\Pages;

use App\Filament\Resources\TradeHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTradeHistory extends EditRecord
{
    protected static string $resource = TradeHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
