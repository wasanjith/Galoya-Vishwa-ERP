<?php

namespace App\Enums;

enum VehicleMaintenanceType: string
{
    case Service = 'service';
    case Tyre = 'tyre';
    case Repair = 'repair';
    case Inspection = 'inspection';

    public function label(): string
    {
        return match ($this) {
            self::Service => 'Scheduled Service',
            self::Tyre => 'Tyre & Wheels',
            self::Repair => 'Breakdown Repair',
            self::Inspection => 'Inspection / Other',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Service => 'info',
            self::Tyre => 'warning',
            self::Repair => 'danger',
            self::Inspection => 'gray',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case): array => [$case->value => $case->label()])
            ->all();
    }
}


