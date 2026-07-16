<?php

namespace App\Enums;

enum Religion: int
{
    case ISLAM = 1;
    case HINDUISM = 2;
    case BUDDHISM = 3;
    case CHRISTIANITY = 4;
    case OTHER = 5;

    public function label(): string
    {
        return __('enums.religion.' . $this->name);
    }
}
