<?php

namespace App\Filament\Resources\FailedImportRowResource\Pages;

use App\Filament\Resources\FailedImportRowResource;
use Filament\Resources\Pages\ListRecords;

class ListFailedImportRows extends ListRecords
{
    protected static string $resource = FailedImportRowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
