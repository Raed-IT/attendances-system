<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\EmployeeResource;
use App\Jobs\CalculateReportsJob;
use App\Models\Device;
use App\Models\Employee;
use App\Traits\SendNotificationsTrait;
use Artisan;
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
                    Checkbox::make("check_finger_print")->label("التحقق من بصمة الاصبع")->default(false),
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
        $notificationsErrors = [];
        if ($zk->connect()) {
            $zk->enableDevice();
            $employees = $zk->getUser();
            foreach ($employees as $employee) {
                try {
                    $employee['device_id'] = $data['device_id'];

                    if ($data["check_finger_print"]) {
                        if (!empty($zk->getFingerprint($employee["uid"]))) {
                            $employee["has_fingerprint"] = true;
                        } else {
                            $employee["has_fingerprint"] = false;
                        }
                    }

                    Employee::updateOrCreate([
                        "uid" => $employee["uid"],
                        "userid" => $employee['userid'],
                        "device_id" => $employee['device_id'],
                    ], $employee);
                } catch (\Exception $e) {
                    array_push($notificationsErrors, Notification::make()->title(" فشل  مزامنة الموظفين" . $employee['userid'])->danger());

                }


            }
            $zk->disableDevice();

            if (!empty($notificationsErrors)) {
                $notificationsErrors[0]->send()->toDatabase();
                foreach ($notificationsErrors as $notification) {
                    $notification->sendToDatabase(auth()->user());
                }
            }
        } else {
            Notification::make()->title("لم بتم الوصول الى الجهاز اكد من الاتصال ")->danger()->send();
        }
    }

}
