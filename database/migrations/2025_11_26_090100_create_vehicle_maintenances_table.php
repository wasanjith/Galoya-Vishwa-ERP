<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicle_maintenances', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->string('maintenance_type');
            $table->date('performed_at');
            $table->text('details')->nullable();
            $table->decimal('cost', 12, 2)->default(0);
            $table->foreignId('driver_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->unsignedBigInteger('mileage_at_service')->nullable();
            $table->date('last_service_date')->nullable();
            $table->date('next_service_date')->nullable();
            $table->date('last_tyre_replace_date')->nullable();
            $table->timestamps();

            $table->index(['vehicle_id', 'maintenance_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_maintenances');
    }
};


