<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use App\Models\Device;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Rats\Zkteco\Lib\ZKTeco;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function callHook(string $hook): void
    {
        if ($hook != "beforeFill" && $hook != "afterFill" && $hook != "beforeValidate") {
//            dd($hook);
        }
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCancelFormAction(),
            Action::make('save')
                ->action(function () {
                    $device =Device::find($this->data["device_id"]);
                    $zk = new ZKTeco($device->ip);
//                    dd($this->data);
                    if ($zk->connect()) {
                        $zk->enableDevice();
                        $zk->setUser($this->data['uid'], $this->data['userid'], $this->data["name"], 123123, $this->data["role"]);
                        $this->create();
                        $zk->disableDevice();
                    } else {
                        Notification::make()->title("فشل الاتصال ");
                    }
                }),

        ];
    }
}
