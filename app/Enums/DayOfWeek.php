<?php

namespace App\Enums;

enum DayOfWeek: int
{
    case Sunday = 0;
    case Monday = 1;
    case Tuesday = 2;
    case Wednesday = 3;
    case Thursday = 4;
    case Friday = 5;
    case Saturday = 6;

    public function label(): string
    {
        return match($this) {
            self::Sunday => __('days.sunday'),
            self::Monday => __('days.monday'),
            self::Tuesday => __('days.tuesday'),
            self::Wednesday => __('days.wednesday'),
            self::Thursday => __('days.thursday'),
            self::Friday => __('days.friday'),
            self::Saturday => __('days.saturday'),
        };
    }
}
