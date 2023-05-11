<?php

namespace App\Enums;

enum AttendanceStateEnum: string
{
    case FINGERPRINT = 'Fingerprint';
    case PASSWORD = 'Password';
    case CARD = 'Card';
    public function name(): string
    {
        return match ($this) {
            self::FINGERPRINT => 'بصمة ',
            self::PASSWORD => 'كلة سر ',
            self::CARD => 'كرت',
         };

    }
    public function color(): string
    {
        return match ($this) {
            self::FINGERPRINT => 'danger',
            self::PASSWORD => 'success',
            self::CARD => 'warning',
         };

    }
}
