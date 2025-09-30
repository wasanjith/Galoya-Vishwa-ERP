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
        Schema::table('raw_material_purchases', function (Blueprint $table) {
            $table->decimal('quantity', 15, 3)->default(0)->after('invoice_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raw_material_purchases', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }
};
