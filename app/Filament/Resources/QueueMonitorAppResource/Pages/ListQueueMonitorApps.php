<?php

namespace App\Filament\Resources\QueueMonitorAppResource\Pages;

use App\Filament\Resources\QueueMonitorAppResource;
use App\Models\QueueMonitoring;
use Croustibat\FilamentJobsMonitor\Resources\QueueMonitorResource\Pages\ListQueueMonitors;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListQueueMonitorApps extends ListQueueMonitors
{
    protected static string $resource = QueueMonitorAppResource::class;

    protected function getActions(): array
    {
        return [
            Actions\Action::make("delete")->color("danger")->action(function(){
                QueueMonitoring::truncate();
            })->label("حذف العناصر الموجوده"),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return QueueMonitoring::query()->where("name","!=","Filament\Notifications\DatabaseNotification")->latest();
    }
}
