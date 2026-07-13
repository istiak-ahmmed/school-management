<?php

namespace App\Enums;

enum FeeFrequency: int
{
    case OneTime   = 0;
    case Monthly   = 1;
    case Quarterly = 2;
    case Yearly    = 3;

    public function label(): string
    {
        return match ($this) {
            self::OneTime   => 'এককালীন',
            self::Monthly   => 'মাসিক',
            self::Quarterly => 'ত্রৈমাসিক',
            self::Yearly    => 'বার্ষিক',
        };
    }
}
