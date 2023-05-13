<?php

namespace App\Enums;

use Illuminate\Support\Collection;

enum PermanenceTypeEnum: string
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
            self::CONSTANT => 'warning',
        };

    }

    public static function colors(): Collection
    {
        return collect([
            self::ADMINISTRATIVE->color() => self::ADMINISTRATIVE->value,
            self::SHIFT->color() => self::SHIFT->value,
            self::CONSTANT->color() => self::CONSTANT->value,
        ]);

    }

    public static function values(): Collection
    {
        return collect([
            self::ADMINISTRATIVE->value => self::ADMINISTRATIVE->name(),
            self::SHIFT->value => self::SHIFT->name(),
            self::CONSTANT->value => self::CONSTANT->name(),
        ]);

    }

}
