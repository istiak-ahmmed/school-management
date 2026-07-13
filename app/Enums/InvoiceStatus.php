<?php

namespace App\Enums;

enum InvoiceStatus: int
{
    case Unpaid    = 0;
    case Partial   = 1;
    case Paid      = 2;
    case Waived    = 3;
    case Cancelled = 4;

    public function label(): string
    {
        return match ($this) {
            self::Unpaid    => 'অপরিশোধিত',
            self::Partial   => 'আংশিক পরিশোধ',
            self::Paid      => 'পরিশোধিত',
            self::Waived    => 'মওকুফ',
            self::Cancelled => 'বাতিল',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Unpaid    => 'red',
            self::Partial   => 'yellow',
            self::Paid      => 'green',
            self::Waived    => 'blue',
            self::Cancelled => 'gray',
        };
    }
}
