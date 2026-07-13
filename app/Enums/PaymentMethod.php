<?php

namespace App\Enums;

enum PaymentMethod: int
{
    case Cash       = 0;
    case BKash      = 1;
    case Nagad      = 2;
    case Rocket     = 3;
    case SSLCommerz = 4;
    case Bank       = 5;
    case Cheque     = 6;

    public function label(): string
    {
        return match ($this) {
            self::Cash       => 'নগদ',
            self::BKash      => 'বিকাশ',
            self::Nagad      => 'নগদ (অ্যাপ)',
            self::Rocket     => 'রকেট',
            self::SSLCommerz => 'SSLCommerz',
            self::Bank       => 'ব্যাংক',
            self::Cheque     => 'চেক',
        };
    }
}
