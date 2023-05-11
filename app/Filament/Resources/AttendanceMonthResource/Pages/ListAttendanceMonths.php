<?php

namespace App\Filament\Resources\AttendanceMonthResource\Pages;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\AttendanceMonthResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAttendanceMonths extends ListRecords
{
    protected static string $resource = AttendanceMonthResource::class;

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
