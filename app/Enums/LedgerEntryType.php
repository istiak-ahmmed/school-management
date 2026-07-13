<?php

namespace App\Enums;

enum LedgerEntryType: int
{
    case Debit  = 0;
    case Credit = 1;

    public function label(): string
    {
        return match ($this) {
            self::Debit  => 'ডেবিট',
            self::Credit => 'ক্রেডিট',
        };
    }
}
