<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QueueMonitorAppResource\Pages;
use App\Filament\Resources\QueueMonitorAppResource\Pages\ListQueueMonitorApps;
use App\Filament\Resources\QueueMonitorAppResource\RelationManagers;
use App\Models\QueueMonitorApp;
use App\Models\QueueMonitoring;
use Croustibat\FilamentJobsMonitor\Resources\QueueMonitorResource;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QueueMonitorAppResource extends QueueMonitorResource
{
    protected static ?string $model = QueueMonitoring::class;

    protected static ?string $label = "الاعمال بالخلفية ";
    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?int $navigationSort = 1;

    public static function getPages(): array
    {
        return [
            'index' => ListQueueMonitorApps::route('/'),
        ];
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return true; // TODO: Change the autogenerated stub
    }
}
