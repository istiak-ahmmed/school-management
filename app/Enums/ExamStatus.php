<?php

namespace App\Enums;

enum ExamStatus: int
{
    case Upcoming = 0;
    case Ongoing = 1;
    case MarksEntry = 2;
    case Published = 3;
    case Archived = 4;

    public function label(): string
    {
        return match ($this) {
            self::Upcoming => 'আসন্ন',
            self::Ongoing => 'চলমান',
            self::MarksEntry => 'মার্কস এন্ট্রি',
            self::Published => 'প্রকাশিত',
            self::Archived => 'আর্কাইভড',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Upcoming => 'blue',
            self::Ongoing => 'indigo',
            self::MarksEntry => 'yellow',
            self::Published => 'emerald',
            self::Archived => 'gray',
        };
    }
}
