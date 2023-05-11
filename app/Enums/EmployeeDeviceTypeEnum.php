<?php

namespace App\Enums;

enum EmployeeDeviceTypeEnum: string
{
    case ADMIN = 'Admin';
    case USER = 'Userr';


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
