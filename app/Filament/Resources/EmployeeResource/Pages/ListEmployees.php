<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\EmployeeResource;
use App\Jobs\CalculateReportsJob;
use App\Models\Device;
use App\Models\Employee;
use App\Models\ErrorSyncModel;
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
        if ($zk->connect()) {
            $zk->enableDevice();
            $employees = $zk->getUser();
            $zk->disableDevice();
            $errors = [];
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
                    array_push($errors, $employee['userid']);
                }


            }
            if (!empty($errors)) {
                $errorString = "";
                foreach ($errors as $error) {
                    $errorString .= $error . '  ,  ';
                }

                $err = ErrorSyncModel::create([
                    "content" => "فشل في مزامنة  الموظفين " . $errorString,
                ]);
                $notification = Notification::make()->title("فشل مزامنة   الموظفين")->body("رقم الخطاء في جدوال الاخطاء " . $err->id)->danger();
                auth()->user()->notify($notification->toDatabase());

            } else {
                $notification = Notification::make()->title("تم مزامنة   الموظفين")->success();
                auth()->user()->notify($notification->toDatabase());
            }
        } else {
            $notification = Notification::make()->title("لم بتم الوصول الى الجهاز اكد من الاتصال ")->danger();
            auth()->user()->notify($notification->toDatabase());
            $notification->send();
        }
    }

}
