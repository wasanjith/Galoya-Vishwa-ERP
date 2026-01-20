<?php

namespace App\Observers;

use App\Enums\VehicleMaintenanceType;
use App\Models\VehicleMaintenance;

class VehicleMaintenanceObserver
{
    /**
     * Sync vehicle snapshots whenever a maintenance record is saved.
     */
    public function saved(VehicleMaintenance $maintenance): void
    {
        $vehicle = $maintenance->vehicle;

        if ($vehicle === null) {
            return;
        }

        $shouldSave = false;

        $serviceDate = $maintenance->last_service_date
            ?? ($maintenance->typeEnum() === VehicleMaintenanceType::Service ? $maintenance->performed_at : null);

        if ($serviceDate !== null && $vehicle->last_service_date !== $serviceDate) {
            $vehicle->last_service_date = $serviceDate;
            $shouldSave = true;
        }

        if ($maintenance->next_service_date !== null && $vehicle->next_service_date !== $maintenance->next_service_date) {
            $vehicle->next_service_date = $maintenance->next_service_date;
            $shouldSave = true;
        }

        $tyreDate = $maintenance->last_tyre_replace_date
            ?? ($maintenance->typeEnum() === VehicleMaintenanceType::Tyre ? $maintenance->performed_at : null);

        if ($tyreDate !== null && $vehicle->last_tyre_replace_date !== $tyreDate) {
            $vehicle->last_tyre_replace_date = $tyreDate;
            $shouldSave = true;
        }

        if ($maintenance->mileage_at_service !== null && $maintenance->mileage_at_service > $vehicle->current_mileage) {
            $vehicle->current_mileage = $maintenance->mileage_at_service;
            $shouldSave = true;
        }

        if ($shouldSave) {
            $vehicle->save();
        }
    }
}


