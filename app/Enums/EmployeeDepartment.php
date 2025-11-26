<?php

namespace App\Enums;

enum EmployeeDepartment: string
{
    case Manufacturing = 'manufacturing';
    case Delivering = 'delivering';

    public function label(): string
    {
        return match ($this) {
            self::Manufacturing => 'Manufacturing',
            self::Delivering => 'Delivering',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case): array => [$case->value => $case->label()])
            ->all();
    }
}


