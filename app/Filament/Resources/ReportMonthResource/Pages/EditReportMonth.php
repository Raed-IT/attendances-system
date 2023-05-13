<?php

namespace App\Filament\Resources\ReportMonthResource\Pages;

use App\Filament\Resources\ReportMonthResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReportMonth extends EditRecord
{
    protected static string $resource = ReportMonthResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
