<?php

namespace App\Enums;

enum PaymentStatus: int
{
    case Success  = 0;
    case Pending  = 1;
    case Failed   = 2;
    case Refunded = 3;

    public function label(): string
    {
        return match ($this) {
            self::Success  => 'সফল',
            self::Pending  => 'অপেক্ষমাণ',
            self::Failed   => 'ব্যর্থ',
            self::Refunded => 'ফেরত',
        };
    }
}
