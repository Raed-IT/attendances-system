<?php

namespace App\Enums;

enum EmployeeDeviceTypeEnum: string
{
    case ADMIN = '14';
    case USER = '0';


    public function name(): string
    {
        return match ($this) {
            self::ADMIN => 'مدير ',
            self::USER => 'موظف',
        };

    }

    public function color(): string
    {
        return match ($this) {
            self::ADMIN => 'danger',
            self::USER => 'secondary',
        };

    }
}
