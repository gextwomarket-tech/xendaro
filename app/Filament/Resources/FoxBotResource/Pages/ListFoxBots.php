<?php

namespace App\Filament\Resources\FoxBotResource\Pages;

use App\Filament\Resources\FoxBotResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFoxBots extends ListRecords
{
    protected static string $resource = FoxBotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
