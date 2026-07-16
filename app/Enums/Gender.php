<?php

namespace App\Enums;

enum Gender: int
{
    case MALE = 1;
    case FEMALE = 2;

    public function label(): string
    {
        return __('enums.gender.' . $this->name);
    }
}
