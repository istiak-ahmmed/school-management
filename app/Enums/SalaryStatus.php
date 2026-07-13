<?php

namespace App\Enums;

enum SalaryStatus: int
{
    case Paid    = 0;
    case Pending = 1;
    case Partial = 2;

    public function label(): string
    {
        return match ($this) {
            self::Paid    => 'পরিশোধিত',
            self::Pending => 'অপেক্ষমাণ',
            self::Partial => 'আংশিক',
        };
    }
}
