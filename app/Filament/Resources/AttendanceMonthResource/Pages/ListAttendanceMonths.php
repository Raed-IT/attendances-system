<?php

namespace App\Filament\Resources\AttendanceMonthResource\Pages;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\AttendanceMonthResource;
use App\Helpers\CalculateAttendances;
use App\Models\Attendance;
use App\Models\AttendanceMonth;
use App\Models\Device;
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
                    $this->syncEmployeeAttendanceFromDevice($data);
                }),


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
    protected function syncEmployeeAttendanceFromDevice($data)
    {

        $zk = new ZKTeco($data["device_ip"]);
        if ($zk->connect()) {
            $zk->enableDevice();
            $attendances = $zk->getAttendance();
            foreach ($attendances as $attendance) {

                try {
                    $user_id = $attendance['id'];
                    $data = Arr::except($attendance, ['id']);
                    $data['user_id'] = $user_id;
                    Attendance::updateOrCreate(['timestamp' => $attendance['timestamp'], "uid" => $attendance['uid']], $data);
                } catch (\Exception $e) {
                    $zk->disableDevice();
                    $this->notifyCurrentUser("فشل مزامنة مزامنة الموظفين" . $attendance["uid"], true);
                }
            }
            $zk->disableDevice();
            $zk->disconnect();

            $this->notifyCurrentUser("تم مزامنة  حركة الموظفين", true, true);

        } else {
            Notification::make()->title("لم بتم الوصول الى الجهاز اكد من الاتصال ")->danger()->send();
        }
    }
}
