<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\AttendanceResource;
use App\Jobs\SyncEmployeeAttendsJob;
use App\Models\Attendance;
use App\Models\Device;
use App\Models\Employee;
use App\Traits\SendNotificationsTrait;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Rats\Zkteco\Lib\ZKTeco;

class ListAttendances extends ListRecords
{
    use  SendNotificationsTrait;

    protected static string $resource = AttendanceResource::class;



    protected function getTableHeaderActions(): array
    {
        return [

            FilamentExportHeaderAction::make('Export')->label("تصدير البيانات ")->button()->color("danger"),

        ];
    }

}
