<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use App\Models\Device;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Pages\Actions\Action;
use Filament\Resources\Resource;
use Rats\Zkteco\Lib\ZKTeco;

class EditEmployee extends EditRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getActions(): array
    {
        return [
//            Actions\DeleteAction::make(),
        ];
    }


    protected function getFormActions(): array
    {
        return [

            Action::make('save')
                ->action(function () {
                    $device = Device::find($this->data['device_id']);
                    $zk = new ZKTeco($device->ip);
                      if ($zk->connect()) {
                        $zk->setUser($this->data['uid'], $this->data['userid'], $this->data["name"], $this->data['password'] ?? 123123, $this->data['role']);
                        $this->save();
                        $this->redirect(EmployeeResource::getUrl());
                    } else {
                          $notification = Notification::make()->title("فشل الاتصال ")->danger()->send()->toDatabase();
                          auth()->user()->notify($notification);
                      }
                }),
            $this->getCancelFormAction(),
        ];
    }
}
