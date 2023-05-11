<?php

namespace App\Enums;

enum RoleTypeEnum: string
{
    case ADMINISTRATIVE = 'administrative'; // دوام اداري
    case SHIFT = 'shift'; //  دوام مناوبات
    case CONSTANT = 'constant'; //  دوام مناوبات
/*
 * 2=> inroller
0=> normal user
4=>user define rol 1
14 => super admin
8=> defined role 2
10 => define role 3*/

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
