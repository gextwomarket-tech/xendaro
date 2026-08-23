<?php

namespace App\Filament\Resources\FaqContentResource\Pages;

use App\Filament\Resources\FaqContentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFaqContents extends ListRecords
{
    protected static string $resource = FaqContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
