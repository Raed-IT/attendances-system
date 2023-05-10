<?php

namespace App\Enums;

enum AttendanceTypeEnum: string
{
    case CHECK_IN = '0';
    case CHECK_OUT = '1';
    public function name(): string
    {
        return match ($this) {
            self::CHECK_IN => 'تسجيل دخول ',
            self::CHECK_OUT => 'تسجيل خروج',
         };

    }
    public function color(): string
    {
        return match ($this) {
            self::CHECK_IN => 'warning',
            self::CHECK_OUT => 'secondary',
         };

    }
}
