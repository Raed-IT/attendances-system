<?php

namespace App\Filament\Resources\ActualSalaryResource\Pages;

use App\Filament\Resources\ActualSalaryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListActualSalaries extends ListRecords
{
    protected static string $resource = ActualSalaryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
