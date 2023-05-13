<?php

namespace App\Filament\Resources\RivalResource\Pages;

use App\Filament\Resources\RivalResource;
use App\Models\Rival;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Builder;

class ManageRivals extends ManageRecords
{
    protected static string $resource = RivalResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


}
