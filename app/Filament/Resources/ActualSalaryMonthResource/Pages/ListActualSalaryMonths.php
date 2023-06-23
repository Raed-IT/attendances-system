<?php

namespace App\Filament\Resources\ActualSalaryMonthResource\Pages;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\ActualSalaryMonthResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListActualSalaryMonths extends ListRecords
{
    protected static string $resource = ActualSalaryMonthResource::class;

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
