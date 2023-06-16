<?php

namespace App\Filament\Resources\QueueMonitorAppResource\Pages;

use App\Filament\Resources\QueueMonitorAppResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQueueMonitorApp extends EditRecord
{
    protected static string $resource = QueueMonitorAppResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
