<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\EmployeeResource;
use App\Models\Device;
use App\Models\Employee;
use App\Traits\SendNotificationsTrait;
use Carbon\Carbon;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Rats\Zkteco\Lib\ZKTeco;


class ListEmployees extends ListRecords
{
    use  SendNotificationsTrait;

    protected static string $resource = EmployeeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make("sync")->label("استيراد الموظفين من جهاز البصمة ")
                ->requiresConfirmation()->modalButton('مزامنة')->form([
                    Select::make("device_ip")->options(fn() => Device::all()->pluck("name", "ip"))
                        ->required()->label("اختر جهاز البصمة")->reactive()->afterStateUpdated(function (callable $set, callable $get) {
                            if ($get("device_ip")) {
                                $device = Device::whereIp($get("device_ip"))->first();
                                $set("device_id", $device->id);
                            }
                        }),
                    Hidden::make("device_id"),
                ])
                ->color("success")->action(function ($data) {
                    $this->syncEmployeeFromDevice($data);
                }),
        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [

            FilamentExportHeaderAction::make('Export')->label("تصدير البيانات ")->button()->color("danger"),

        ];
    }

    protected function syncEmployeeFromDevice($data)
    {
        $zk = new ZKTeco($data["device_ip"]);
        if ($zk->connect()) {
            $zk->enableDevice();
            $employees = $zk->getUser();
            try {
                foreach ($employees as $employee) {
                    $employee['device_id'] = $data['device_id'];
                    Employee::updateOrCreate([
                        "uid" => $employee["uid"],
                        "userid" => $employee['userid']
                    ], $employee);
                }
                $zk->disableDevice();
                $this->notifyCurrentUser("تم مزامنة الموظفين", true, true);
            } catch (\Exception $e) {
                info($e);
                $zk->disableDevice();
                Notification::make()->title(" فشل  مزامنة الموظفين")->danger()->send();
            }
        } else {
            Notification::make()->title("لم بتم الوصول الى الجهاز اكد من الاتصال ")->danger()->send();
        }
    }

}
