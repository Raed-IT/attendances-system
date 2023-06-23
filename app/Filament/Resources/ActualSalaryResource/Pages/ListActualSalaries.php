<?php

namespace App\Filament\Resources\ActualSalaryResource\Pages;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
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

    protected function getTableHeaderActions(): array
    {
        return [

            FilamentExportHeaderAction::make('Export')->label("تصدير البيانات ")->button()->color("danger"),

        ];
    }
}
