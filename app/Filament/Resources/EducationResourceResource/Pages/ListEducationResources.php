<?php

namespace App\Filament\Resources\EducationResourceResource\Pages;

use App\Filament\Resources\EducationResourceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEducationResources extends ListRecords
{
    protected static string $resource = EducationResourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
