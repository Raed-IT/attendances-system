<?php

namespace App\Filament\Resources\ErrorSynckResource\Pages;

use App\Filament\Resources\ErrorSynckResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageErrorSyncks extends ManageRecords
{
    protected static string $resource = ErrorSynckResource::class;

    protected function getActions(): array
    {
        return [
//            Actions\CreateAction::make(),
        ];
    }
}
