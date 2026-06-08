<?php

namespace App\Filament\Resources\TicketReplyResource\Pages;

use App\Filament\Resources\TicketReplyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTicketReplies extends ListRecords
{
    protected static string $resource = TicketReplyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
