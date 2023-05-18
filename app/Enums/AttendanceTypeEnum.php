<?php

namespace App\Enums;

use FontLib\Table\Type\name;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Pure;

enum AttendanceTypeEnum: string
{
    case CHECK_IN = '0';
    case CHECK_OUT = '1';
    case OVER_TIME_IN = '4';
    case OVER_TIME_OUT = '5';
    case ERROR = '255';

    public function name(): string
    {
        return match ($this) {
            self::CHECK_IN => 'تسجيل دخول ',
            self::CHECK_OUT => 'تسجيل خروج',
            self::OVER_TIME_IN => ' تسجيل دخول اضافي ',
            self::OVER_TIME_OUT => 'تسجيل خروج اضافي',
            self::ERROR => 'خطاء',
        };

    }

    public function color(): string
    {
        return match ($this) {
            self::CHECK_IN => 'success',
            self::CHECK_OUT => 'danger',
            self::OVER_TIME_IN => 'warning',
            self::OVER_TIME_OUT => 'info',
            self::ERROR => 'primary',
        };

    }

    public static function colors(): Collection
    {
        return collect([
            self::CHECK_IN->color() => self::CHECK_IN->value,
            self::CHECK_OUT->color() => self::CHECK_OUT->value,
            self::OVER_TIME_IN->color() => self::OVER_TIME_IN->value,
            self::OVER_TIME_OUT->color() => self::OVER_TIME_OUT->value,
            self::ERROR->color() => self::ERROR->value,
        ]);

    }

    public static function values(): Collection
    {
        return collect([
            self::CHECK_IN->value => self::CHECK_IN->name(),
            self::CHECK_OUT->value => self::CHECK_OUT->name(),
            self::OVER_TIME_IN->value => self::OVER_TIME_IN->name(),
            self::OVER_TIME_OUT->value => self::OVER_TIME_OUT->name(),
        ]);

    }

}
