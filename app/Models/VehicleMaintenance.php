<?php

namespace App\Models;

use App\Enums\VehicleMaintenanceType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleMaintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'maintenance_type',
        'performed_at',
        'details',
        'cost',
        'driver_id',
        'mileage_at_service',
        'last_service_date',
        'next_service_date',
        'last_tyre_replace_date',
    ];

    protected $casts = [
        'performed_at' => 'date',
        'cost' => 'decimal:2',
        'last_service_date' => 'date',
        'next_service_date' => 'date',
        'last_tyre_replace_date' => 'date',
    ];

    /**
     * @return BelongsTo<Vehicle, VehicleMaintenance>
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * @return BelongsTo<Employee, VehicleMaintenance>
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'driver_id');
    }

    public function typeEnum(): ?VehicleMaintenanceType
    {
        return VehicleMaintenanceType::tryFrom((string) $this->maintenance_type);
    }
}


