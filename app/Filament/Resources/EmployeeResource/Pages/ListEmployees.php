<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use App\Models\Device;
use App\Models\Employee;
use Carbon\Carbon;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Rats\Zkteco\Lib\ZKTeco;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make("sync")->label("استيراد الموظفين من جهاز البصمة ")
                ->requiresConfirmation()->modalButton('مزامنة')->form([
                    Select::make("device_ip")->options(fn() => Device::all()->pluck("name", "ip"))->required()
                ])
                ->color("success")->action(function ($data) {
                    $this->syncEmployeeFromDevice($data);
                }),
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
                    Employee::updateOrCreate(["uid" => $employee["uid"]], $employee);
                }
                Notification::make()->title("تم ")->success()->send();
            } catch (\Exception $e) {
                Notification::make()->title("خطاء")->danger()->send();
            }
        } else {
            Notification::make()->title("لم بتم الوصول الى الجهاز اكد من الاتصال ")->danger()->send();
        }
    }
}
