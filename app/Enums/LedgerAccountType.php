<?php

namespace App\Enums;

enum LedgerAccountType: int
{
    case Income    = 0;
    case Expense   = 1;
    case Asset     = 2;
    case Liability = 3;

    public function label(): string
    {
        return match ($this) {
            self::Income    => 'আয়',
            self::Expense   => 'ব্যয়',
            self::Asset     => 'সম্পদ',
            self::Liability => 'দায়',
        };
    }
}
