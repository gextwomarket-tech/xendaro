<?php

namespace App\Filament\Resources\TicketReplyResource\Pages;

use App\Filament\Resources\TicketReplyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTicketReply extends EditRecord
{
    protected static string $resource = TicketReplyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
