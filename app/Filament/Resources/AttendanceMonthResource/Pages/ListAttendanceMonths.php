<?php

namespace App\Filament\Resources\AttendanceMonthResource\Pages;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\AttendanceMonthResource;
use App\Helpers\CalculateAttendances;
use App\Models\AttendanceMonth;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAttendanceMonths extends ListRecords
{
    protected static string $resource = AttendanceMonthResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make("calc")
                ->requiresConfirmation()
                ->modalButton("تحليل")
                ->action(function () {
                    CalculateAttendances::calculateHoras();
                })->label("تحليل بيانات الموظفين")
        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [

            FilamentExportHeaderAction::make('Export')->label("تصدير البيانات ")->button()->color("danger"),

        ];
    }
}
