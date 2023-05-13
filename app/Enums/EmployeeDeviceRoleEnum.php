<?php

namespace App\Enums;

use Illuminate\Support\Collection;

enum EmployeeDeviceRoleEnum: string
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
            self::ADMIN => 'success',
            self::USER => 'secondary',
        };

    }

    public static function colors(): Collection
    {
        return collect([
            self::ADMIN->color() => intval(self::ADMIN->value),
            self::USER->color() => intval(self::USER->value),
        ]);

    }


    public static function values(): Collection
    {
        return collect([
            intval(self::ADMIN->value) => self::ADMIN->name(),
            intval(self::USER->value)=> self::USER->name(),
        ]);

    }
}
