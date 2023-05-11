<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
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


    protected function getTableQuery(): Builder
    {
        return Attendance::query()->orderByDesc("timestamp");
    }

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
                })
        ];
    }


    protected function syncEmployeeAttendanceFromDevice($data)
    {
        $zk = new ZKTeco($data["device_ip"]);
        if ($zk->connect()) {
            $zk->enableDevice();
            $attendances = $zk->getAttendance();
            try {
                foreach ($attendances as $attendance) {

                    $user_id = $attendance['id'];
                    $data = Arr::except($attendance, ['id']);
                    $data['user_id'] = $user_id;
                    Attendance::updateOrCreate(['timestamp' => $attendance['timestamp'], "uid" => $attendance['uid']], $data);
                }
                $zk->disableDevice();
                $zk->disconnect();

                $this->notifyCurrentUser("تم مزامنة  حركة الموظفين", true, true);
            } catch (\Exception $e) {
                $zk->disableDevice();
                Notification::make()->title(" فشل مزامنة مزامنة الموظفين")->danger()->send();
            }
        } else {
            Notification::make()->title("لم بتم الوصول الى الجهاز اكد من الاتصال ")->danger()->send();
        }
    }
}
