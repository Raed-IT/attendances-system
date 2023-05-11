<?php

namespace App\Enums;

enum AttendanceTypeEnum: string
{
    case CHECK_IN = 'Check-in';
    case CHECK_OUT = 'Check-out';
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
            self::CHECK_IN => 'danger',
            self::CHECK_OUT => 'success',
         };

    }
}
