<?php

namespace App\Filament\Resources\FoxBotResource\Pages;

use App\Filament\Resources\FoxBotResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFoxBot extends EditRecord
{
    protected static string $resource = FoxBotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
