<?php

namespace App\Filament\Resources\AttendanceMonthResource\Pages;

use App\Filament\Resources\AttendanceMonthResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttendanceMonth extends EditRecord
{
    protected static string $resource = AttendanceMonthResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
