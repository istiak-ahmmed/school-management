<?php

namespace App\Enums;

enum SalaryPaymentMethod: int
{
    case Cash  = 0;
    case Bank  = 1;
    case BKash = 2;

    public function label(): string
    {
        return match ($this) {
            self::Cash  => 'নগদ',
            self::Bank  => 'ব্যাংক',
            self::BKash => 'বিকাশ',
        };
    }
}
