<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function callHook(string $hook): void
    {
        if ($hook != "beforeFill" && $hook != "afterFill" && $hook != "beforeValidate") {
//            dd($hook);
        }
    }
}
