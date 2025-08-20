<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RawMaterialPurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some existing raw materials and suppliers
        $rawMaterials = DB::table('raw_materials')->limit(5)->get();
        $suppliers = DB::table('suppliers')->get();

        if ($rawMaterials->isEmpty() || $suppliers->isEmpty()) {
            $this->command->info('No raw materials or suppliers found. Please run RawMaterialSeeder first.');
            return;
        }

        $purchases = [
            [
                'raw_material_id' => $rawMaterials->first()->id,
                'supplier_id' => $suppliers->first()->id,
                'invoice_number' => 'INV-2024-001',
                'quantity' => 100.000,
                'total_amount' => 15000.00,
                'payment_status' => 'paid',
                'amount_paid' => 15000.00,
                'balance_amount' => 0.00,
                'invoice_soft_copy' => null,
                'invoice_file_type' => null,
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(30),
            ],
            [
                'raw_material_id' => $rawMaterials->skip(1)->first()->id,
                'supplier_id' => $suppliers->skip(1)->first()->id,
                'invoice_number' => 'INV-2024-002',
                'quantity' => 150.000,
                'total_amount' => 25000.00,
                'payment_status' => 'partial',
                'amount_paid' => 15000.00,
                'balance_amount' => 10000.00,
                'invoice_soft_copy' => null,
                'invoice_file_type' => null,
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(15),
            ],
            [
                'raw_material_id' => $rawMaterials->skip(2)->first()->id,
                'supplier_id' => $suppliers->first()->id,
                'invoice_number' => 'INV-2024-003',
                'quantity' => 50.000,
                'total_amount' => 8000.00,
                'payment_status' => 'pending',
                'amount_paid' => 0.00,
                'balance_amount' => 8000.00,
                'invoice_soft_copy' => null,
                'invoice_file_type' => null,
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
            ],
            [
                'raw_material_id' => $rawMaterials->skip(3)->first()->id,
                'supplier_id' => $suppliers->skip(2)->first()->id,
                'invoice_number' => 'INV-2024-004',
                'quantity' => 75.000,
                'total_amount' => 12000.00,
                'payment_status' => 'overdue',
                'amount_paid' => 5000.00,
                'balance_amount' => 7000.00,
                'invoice_soft_copy' => null,
                'invoice_file_type' => null,
                'created_at' => now()->subDays(45),
                'updated_at' => now()->subDays(45),
            ],
            [
                'raw_material_id' => $rawMaterials->skip(4)->first()->id,
                'supplier_id' => $suppliers->first()->id,
                'invoice_number' => 'INV-2024-005',
                'quantity' => 200.000,
                'total_amount' => 18000.00,
                'payment_status' => 'paid',
                'amount_paid' => 18000.00,
                'balance_amount' => 0.00,
                'invoice_soft_copy' => null,
                'invoice_file_type' => null,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
        ];

        DB::table('raw_material_purchases')->insert($purchases);

        $this->command->info('Raw Material Purchases seeded successfully!');
    }
}
