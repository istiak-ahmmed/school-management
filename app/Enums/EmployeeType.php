<?php

namespace App\Enums;

enum EmployeeType: int
{
    case Teacher = 0;
    case Staff   = 1;

    public function label(): string
    {
        return match ($this) {
            self::Teacher => 'শিক্ষক',
            self::Staff   => 'কর্মচারী',
        };
    }
}
