<?php

namespace App\Filament\Widgets;

use App\Models\Device;
use Filament\Tables;
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
            Tables\Columns\TextColumn::make('name'),
            Tables\Columns\TextColumn::make('ip')
                ->label('Customer'),
        ];
    }

    protected function getTableActions(): array
    {
        return [

        ];
    }

    protected function getTableBulkActions(): array
    {
        return [BulkAction::make("ds")];
    }

    protected function connect(Device $device)
    {

    }
}
