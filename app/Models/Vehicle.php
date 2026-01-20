<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'number_plate',
        'current_mileage',
        'last_service_date',
        'next_service_date',
        'tyre_condition',
        'net_worth',
        'last_tyre_replace_date',
        'diesel_efficiency',
    ];

    protected $casts = [
        'last_service_date' => 'date',
        'next_service_date' => 'date',
        'last_tyre_replace_date' => 'date',
        'net_worth' => 'decimal:2',
        'diesel_efficiency' => 'decimal:2',
    ];

    /**
     * @return HasMany<VehicleMaintenance>
     */
    public function maintenances(): HasMany
    {
        return $this->hasMany(VehicleMaintenance::class);
    }

    public function displayLabel(): string
    {
        return sprintf('%s (%s)', $this->number_plate, $this->name);
    }
}


