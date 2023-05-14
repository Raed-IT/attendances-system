<?php

namespace App\Filament\Resources\ActualSalaryResource\Pages;

use App\Filament\Resources\ActualSalaryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditActualSalary extends EditRecord
{
    protected static string $resource = ActualSalaryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
