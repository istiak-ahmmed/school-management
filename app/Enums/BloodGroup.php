<?php

namespace App\Enums;

enum BloodGroup: int
{
    case A_PLUS = 1;
    case A_MINUS = 2;
    case B_PLUS = 3;
    case B_MINUS = 4;
    case O_PLUS = 5;
    case O_MINUS = 6;
    case AB_PLUS = 7;
    case AB_MINUS = 8;

    public function label(): string
    {
        return __('enums.blood_group.' . $this->name);
    }
}
