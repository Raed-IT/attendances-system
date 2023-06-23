<?php

namespace App\Filament\Resources\AttendanceMonthResource\Pages;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\AttendanceMonthResource;
use App\Filament\Resources\ErrorSynckResource;
use App\Jobs\CalculateReportsJob;

use App\Jobs\SyncEmployeeAttendsJob;
use App\Models\Attendance;
use App\Models\Device;
use App\Models\ErrorSyncModel;
use App\Traits\SendNotificationsTrait;
use Filament\Forms\Components\Select;

use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Arr;
use Rats\Zkteco\Lib\ZKTeco;


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
                    Notification::make()->title("بدات عملية المزامنة")->success()->send();
                    SyncEmployeeAttendsJob::dispatch($data, auth()->user());
                }),
            Actions\Action::make("calc")
                ->requiresConfirmation()
                ->modalButton("تحليل")
                ->action(function () {
                    Notification::make()->title("بدات عملية تحليل")->success()->send();
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
