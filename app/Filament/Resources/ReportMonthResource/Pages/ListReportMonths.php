<?php

namespace App\Filament\Resources\ReportMonthResource\Pages;

use App\Filament\Resources\ReportMonthResource;
use App\Jobs\CalculateSalariesJob;
use App\Jobs\SyncEmployeeAttendsJob;
use App\Models\Device;
use Filament\Forms\Components\Select;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReportMonths extends ListRecords
{
    protected static string $resource = ReportMonthResource::class;

    protected function getActions(): array
    {
        return [
            Actions\Action::make("clacSalary")->label("حساب الرواتب")
                ->color("success")
                ->requiresConfirmation()->action(function () {
                    CalculateSalariesJob::dispatch(auth()->user());
                }),
//            Actions\CreateAction::make(),
        ];
    }
}
