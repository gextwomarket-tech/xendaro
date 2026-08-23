<?php

namespace App\Filament\Resources\EducationResourceResource\Pages;

use App\Filament\Resources\EducationResourceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEducationResource extends EditRecord
{
    protected static string $resource = EducationResourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
