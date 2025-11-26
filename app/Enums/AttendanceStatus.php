<?php

namespace App\Enums;

enum AttendanceStatus: string
{
    case Present = 'present';
    case HalfDay = 'half_day';
    case Absent = 'absent';
    case Leave = 'leave';

    public function label(): string
    {
        return match ($this) {
            self::Present => 'Present',
            self::HalfDay => 'Half Day',
            self::Absent => 'Absent',
            self::Leave => 'Leave',
        };
    }

    public function multiplier(): float
    {
        return match ($this) {
            self::Present => 1,
            self::HalfDay => 0.5,
            self::Absent => 0,
            self::Leave => 0,
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Present => 'success',
            self::HalfDay => 'warning',
            self::Leave => 'info',
            self::Absent => 'danger',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case): array => [$case->value => $case->label()])
            ->all();
    }
}


