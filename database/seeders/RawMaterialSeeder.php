<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RawMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Note: Units and Categories should already be seeded by DatabaseSeeder
        // No need to call them again here

        // Create some sample suppliers if they don't exist
        $supplierIds = $this->createSampleSuppliers();

        // Get category IDs
        $packagingCategoryId = DB::table('raw_material_categories')->where('name', 'Packaging Materials')->first()->id;
        $chemicalCategoryId = DB::table('raw_material_categories')->where('name', 'Chemical Materials')->first()->id;
        $dairyCategoryId = DB::table('raw_material_categories')->where('name', 'Dairy Ingredients')->first()->id;
        $sweetenerCategoryId = DB::table('raw_material_categories')->where('name', 'Sweeteners')->first()->id;
        $flavorCategoryId = DB::table('raw_material_categories')->where('name', 'Flavoring Agents')->first()->id;
        $stabilizerCategoryId = DB::table('raw_material_categories')->where('name', 'Stabilizers & Thickeners')->first()->id;

        // Get unit IDs
        $kgUnitId = DB::table('units')->where('symbol', 'Kg')->first()->id;
        $lUnitId = DB::table('units')->where('symbol', 'L')->first()->id;
        $mlUnitId = DB::table('units')->where('symbol', 'ml')->first()->id;
        $pcsUnitId = DB::table('units')->where('symbol', 'pcs')->first()->id;

        $rawMaterials = [
            // Packaging Materials
            [
                'name' => 'Vanilla Yoghurt Cups',
                'category_id' => $packagingCategoryId,
                'unit_id' => $pcsUnitId,
                'cost_per_unit' => 48.00,
                'minimum_stock_level' => 1000,
                'supplier_id' => $supplierIds['packaging'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jelly Yoghurt Cups',
                'category_id' => $packagingCategoryId,
                'unit_id' => $pcsUnitId,
                'cost_per_unit' => 58.00,
                'minimum_stock_level' => 800,
                'supplier_id' => $supplierIds['packaging'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pany Yoghurt Cups',
                'category_id' => $packagingCategoryId,
                'unit_id' => $pcsUnitId,
                'cost_per_unit' => 51.00,
                'minimum_stock_level' => 900,
                'supplier_id' => $supplierIds['packaging'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cup Lids',
                'category_id' => $packagingCategoryId,
                'unit_id' => $pcsUnitId,
                'cost_per_unit' => 16.00,
                'minimum_stock_level' => 2000,
                'supplier_id' => $supplierIds['packaging'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cup Sleeves',
                'category_id' => $packagingCategoryId,
                'unit_id' => $pcsUnitId,
                'cost_per_unit' => 26.00,
                'minimum_stock_level' => 1500,
                'supplier_id' => $supplierIds['packaging'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Chemical Materials
            [
                'name' => 'Sugar',
                'category_id' => $sweetenerCategoryId,
                'unit_id' => $kgUnitId,
                'cost_per_unit' => 384.00,
                'minimum_stock_level' => 100,
                'supplier_id' => $supplierIds['chemical'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gelatin',
                'category_id' => $stabilizerCategoryId,
                'unit_id' => $kgUnitId,
                'cost_per_unit' => 2720.00,
                'minimum_stock_level' => 20,
                'supplier_id' => $supplierIds['chemical'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Essence (Vanilla)',
                'category_id' => $flavorCategoryId,
                'unit_id' => $mlUnitId,
                'cost_per_unit' => 48.00,
                'minimum_stock_level' => 500,
                'supplier_id' => $supplierIds['chemical'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Essence (Strawberry)',
                'category_id' => $flavorCategoryId,
                'unit_id' => $mlUnitId,
                'cost_per_unit' => 58.00,
                'minimum_stock_level' => 400,
                'supplier_id' => $supplierIds['chemical'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Essence (Mango)',
                'category_id' => $flavorCategoryId,
                'unit_id' => $mlUnitId,
                'cost_per_unit' => 51.00,
                'minimum_stock_level' => 450,
                'supplier_id' => $supplierIds['chemical'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Dairy Ingredients
            [
                'name' => 'Fresh Milk',
                'category_id' => $dairyCategoryId,
                'unit_id' => $lUnitId,
                'cost_per_unit' => 256.00,
                'minimum_stock_level' => 200,
                'supplier_id' => $supplierIds['dairy'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cream',
                'category_id' => $dairyCategoryId,
                'unit_id' => $lUnitId,
                'cost_per_unit' => 800.00,
                'minimum_stock_level' => 50,
                'supplier_id' => $supplierIds['dairy'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Yogurt Culture',
                'category_id' => $dairyCategoryId,
                'unit_id' => $kgUnitId,
                'cost_per_unit' => 8000.00,
                'minimum_stock_level' => 5,
                'supplier_id' => $supplierIds['dairy'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Additional Essential Raw Materials
            [
                'name' => 'Corn Starch',
                'category_id' => $stabilizerCategoryId,
                'unit_id' => $kgUnitId,
                'cost_per_unit' => 576.00,
                'minimum_stock_level' => 30,
                'supplier_id' => $supplierIds['chemical'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Citric Acid',
                'category_id' => $stabilizerCategoryId,
                'unit_id' => $kgUnitId,
                'cost_per_unit' => 1024.00,
                'minimum_stock_level' => 15,
                'supplier_id' => $supplierIds['chemical'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Natural Color (Red)',
                'category_id' => $flavorCategoryId,
                'unit_id' => $mlUnitId,
                'cost_per_unit' => 80.00,
                'minimum_stock_level' => 200,
                'supplier_id' => $supplierIds['chemical'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Natural Color (Yellow)',
                'category_id' => $flavorCategoryId,
                'unit_id' => $mlUnitId,
                'cost_per_unit' => 70.00,
                'minimum_stock_level' => 200,
                'supplier_id' => $supplierIds['chemical'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('raw_materials')->insert($rawMaterials);
    }

    /**
     * Create sample suppliers for the raw materials
     */
    private function createSampleSuppliers(): array
    {
        $suppliers = [
            [
                'name' => 'Premium Packaging Solutions',
                'phone' => '+1-555-0101',
                'address' => '123 Packaging Ave, Industrial District',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ChemCorp Industries',
                'phone' => '+1-555-0102',
                'address' => '456 Chemical Blvd, Science Park',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fresh Dairy Farms',
                'phone' => '+1-555-0103',
                'address' => '789 Farm Road, Countryside',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $supplierIds = [];
        foreach ($suppliers as $supplier) {
            $existingSupplier = DB::table('suppliers')->where('name', $supplier['name'])->first();
            if (!$existingSupplier) {
                $id = DB::table('suppliers')->insertGetId($supplier);
                if (str_contains($supplier['name'], 'Packaging')) {
                    $supplierIds['packaging'] = $id;
                } elseif (str_contains($supplier['name'], 'Chem')) {
                    $supplierIds['chemical'] = $id;
                } elseif (str_contains($supplier['name'], 'Dairy')) {
                    $supplierIds['dairy'] = $id;
                }
            } else {
                if (str_contains($supplier['name'], 'Packaging')) {
                    $supplierIds['packaging'] = $existingSupplier->id;
                } elseif (str_contains($supplier['name'], 'Chem')) {
                    $supplierIds['chemical'] = $existingSupplier->id;
                } elseif (str_contains($supplier['name'], 'Dairy')) {
                    $supplierIds['dairy'] = $existingSupplier->id;
                }
            }
        }

        return $supplierIds;
    }
}
