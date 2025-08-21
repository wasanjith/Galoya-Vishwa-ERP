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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->enum('movement_type', ['purchase', 'sale', 'transfer', 'adjustment', 'return']);
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('batch_number')->nullable(); // For batch tracking
            $table->decimal('quantity', 10, 2); // Positive for in, negative for out
            $table->text('notes')->nullable(); // Additional notes about the movement
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
