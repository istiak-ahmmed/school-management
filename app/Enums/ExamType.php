<?php

namespace App\Enums;

enum ExamType: int
{
    case Monthly = 0;
    case HalfYearly = 1;
    case Annual = 2;
    case Test = 3;
    case Special = 4;

    public function label(): string
    {
        return match ($this) {
            self::Monthly => 'মাসিক পরীক্ষা',
            self::HalfYearly => 'অর্ধ-বার্ষিক পরীক্ষা',
            self::Annual => 'বার্ষিক পরীক্ষা',
            self::Test => 'টেস্ট পরীক্ষা',
            self::Special => 'বিশেষ পরীক্ষা',
        };
    }
}
