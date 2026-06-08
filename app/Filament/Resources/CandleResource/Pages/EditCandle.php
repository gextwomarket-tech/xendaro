<?php

namespace App\Filament\Resources\CandleResource\Pages;

use App\Filament\Resources\CandleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCandle extends EditRecord
{
    protected static string $resource = CandleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
