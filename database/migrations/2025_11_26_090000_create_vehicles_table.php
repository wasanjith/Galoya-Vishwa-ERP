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
        Schema::create('vehicles', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('number_plate')->unique();
            $table->unsignedBigInteger('current_mileage')->default(0);
            $table->date('last_service_date')->nullable();
            $table->date('next_service_date')->nullable();
            $table->string('tyre_condition')->nullable();
            $table->decimal('net_worth', 15, 2)->default(0);
            $table->date('last_tyre_replace_date')->nullable();
            $table->decimal('diesel_efficiency', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};


