<?php

namespace App\Filament\Widgets;

use App\Models\Device;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Rats\Zkteco\Lib\ZKTeco;

class DeviceList extends BaseWidget
{
    protected function getTableQuery(): Builder
    {

        return Device::query()->latest();
    }


    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')->label("اسم الجهاز"),
            Tables\Columns\TextColumn::make('ip')
                ->label('IP'),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('celar admin ')
                    ->label('مسح المدراء')
                    ->action(function (Device $record) {
                        $zk = new ZKTeco($record->ip);
                        if ($zk->connect()) {
                            $zk->deviceName();
                            $zk->clearAdmin();
                            $zk->disableDevice();
                            Notification::make()->title("تم مسح المدراء من الجهاز")->success()->send()->toDatabase();
                        } else {
                            Notification::make()->title("لم يتم الاتصال بالجهاز")->danger()->send()->toDatabase();
                        }
                    }
                    )->requiresConfirmation()
                    ->color("warning"),
                Action::make('test sound ')
                    ->label('تجربة الصوت')
                    ->action(function (Device $record) {
                        $zk = new ZKTeco($record->ip);
                        if ($zk->connect()) {
                            $zk->deviceName();
                            $zk->testVoice();
                            $zk->disableDevice();
                            Notification::make()->title("تم اصدار صوت  من الجهاز")->success()->send()->toDatabase();
                        } else {
                            Notification::make()->title("لم يتم الاتصال بالجهاز")->danger()->send()->toDatabase();
                        }
                    }
                    )
                    ->color("success"),
                Action::make('restart ')
                    ->label('اعادة تشغيل الحهاز')
                    ->action(function (Device $record) {
                        $zk = new ZKTeco($record->ip);
                        if ($zk->connect()) {
                            $zk->deviceName();
                            $zk->restart();
                            $zk->disableDevice();
                            Notification::make()->title("تم اعادة تشغيل الجهاز")->success()->send()->toDatabase();
                        } else {
                            Notification::make()->title("لم يتم الاتصال بالجهاز")->danger()->send()->toDatabase();
                        }
                    }
                    )
                    ->color("danger"),
                Action::make('celar users ')
                    ->label('مسح جميع المستخدمين')
                    ->action(function (Device $record) {
                        $zk = new ZKTeco($record->ip);
                        if ($zk->connect()) {
                            $zk->deviceName();
                            $zk->clearUsers();
                            $zk->disableDevice();
                            Notification::make()->title("تم مسح المستخدمين من الجهاز")->success()->send()->toDatabase();
                        } else {
                            Notification::make()->title("لم يتم الاتصال بالجهاز")->danger()->send()->toDatabase();
                        }
                    }
                    )->requiresConfirmation()
                    ->color("danger"),
            ]),
        ];
    }


    protected function connect(Device $device)
    {

    }
}
