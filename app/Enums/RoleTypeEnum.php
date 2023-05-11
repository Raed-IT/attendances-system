<?php

namespace App\Enums;

enum RoleTypeEnum: string
{
    case ADMINISTRATIVE = 'administrative'; // دوام اداري
    case SHIFT = 'shift'; //  دوام مناوبات
    case CONSTANT = 'constant'; //  دوام مناوبات


    public function name(): string
    {
        return match ($this) {
            self::ADMINISTRATIVE => 'اداري',
            self::SHIFT => 'مناوبات',
            self::CONSTANT => 'راتب ثابت',
        };

    }

    public function color(): string
    {
        return match ($this) {
            self::ADMINISTRATIVE => 'danger',
            self::SHIFT => 'secondary',
            self::CONSTANT => 'danger',
        };

    }
}
