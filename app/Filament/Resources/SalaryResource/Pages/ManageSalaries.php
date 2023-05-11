<?php

namespace App\Filament\Resources\SalaryResource\Pages;

use App\Filament\Resources\SalaryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSalaries extends ManageRecords
{
    protected static string $resource = SalaryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
