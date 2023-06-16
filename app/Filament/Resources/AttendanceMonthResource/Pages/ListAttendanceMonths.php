<?php

namespace App\Filament\Resources\AttendanceMonthResource\Pages;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\AttendanceMonthResource;
use App\Jobs\CalculateReportsJob;

use App\Jobs\SyncEmployeeAttendsJob;
use App\Models\Device;
use App\Traits\SendNotificationsTrait;
use Filament\Forms\Components\Select;

use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;


class ListAttendanceMonths extends ListRecords
{
    use  SendNotificationsTrait;

    protected static string $resource = AttendanceMonthResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),

            Actions\Action::make("syncAttendance")->label("مزامنة حركة الموظفين")
                ->color("success")
                ->modalButton('مزامنة')->form([
                    Select::make("device_ip")->options(fn() => Device::all()->pluck("name", "ip"))
                        ->required()->label("اختر جهاز البصمة"),
                ])
                ->requiresConfirmation()->action(function ($data) {
                    SyncEmployeeAttendsJob::dispatch($data, auth()->user());
                }),
            Actions\Action::make("calc")
                ->requiresConfirmation()
                ->modalButton("تحليل")
                ->action(function () {
                    CalculateReportsJob::dispatch(auth()->user());
//                    \Artisan::call("queue:listen");
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
