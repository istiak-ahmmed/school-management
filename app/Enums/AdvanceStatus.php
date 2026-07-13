<?php

namespace App\Enums;

enum AdvanceStatus: int
{
    case Pending        = 0;
    case Approved       = 1;
    case Rejected       = 2;
    case FullyRecovered = 3;

    public function label(): string
    {
        return match ($this) {
            self::Pending        => 'অপেক্ষমাণ',
            self::Approved       => 'অনুমোদিত',
            self::Rejected       => 'প্রত্যাখ্যাত',
            self::FullyRecovered => 'সম্পূর্ণ পুনরুদ্ধার',
        };
    }
}
