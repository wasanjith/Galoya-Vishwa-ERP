<?php

namespace Tests\Feature;

use App\Models\RawMaterial;
use App\Models\RawMaterialPurchase;
use App\Models\RawMaterialStock;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RawMaterialStockUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that raw material stock is increased when a purchase is created.
     */
    public function test_raw_material_stock_increases_when_purchase_created()
    {
        // Create test data
        $rawMaterial = RawMaterial::factory()->create();
        $supplier = Supplier::factory()->create();

        // Verify initial stock is 0 or doesn't exist
        $initialStock = RawMaterialStock::where('raw_material_id', $rawMaterial->id)->first();
        $this->assertNull($initialStock);

        // Create a purchase
        $purchase = RawMaterialPurchase::create([
            'raw_material_id' => $rawMaterial->id,
            'supplier_id' => $supplier->id,
            'invoice_number' => 'INV-001',
            'quantity' => 100.5,
            'total_amount' => 1000.00,
            'payment_status' => 'pending',
            'amount_paid' => 0,
        ]);

        // Verify stock was created and updated
        $stock = RawMaterialStock::where('raw_material_id', $rawMaterial->id)->first();
        $this->assertNotNull($stock);
        $this->assertEquals(100.5, $stock->quantity_available);
        $this->assertNotNull($stock->last_updated);
    }

    /**
     * Test that raw material stock is adjusted when a purchase quantity is updated.
     */
    public function test_raw_material_stock_adjusts_when_purchase_quantity_updated()
    {
        // Create test data
        $rawMaterial = RawMaterial::factory()->create();
        $supplier = Supplier::factory()->create();

        // Create initial purchase
        $purchase = RawMaterialPurchase::create([
            'raw_material_id' => $rawMaterial->id,
            'supplier_id' => $supplier->id,
            'invoice_number' => 'INV-002',
            'quantity' => 50.0,
            'total_amount' => 500.00,
            'payment_status' => 'pending',
            'amount_paid' => 0,
        ]);

        // Verify initial stock
        $stock = RawMaterialStock::where('raw_material_id', $rawMaterial->id)->first();
        $this->assertEquals(50.0, $stock->quantity_available);

        // Update purchase quantity
        $purchase->update(['quantity' => 75.0]);

        // Verify stock was adjusted (increased by 25.0)
        $stock->refresh();
        $this->assertEquals(75.0, $stock->quantity_available);
    }

    /**
     * Test that raw material stock is reduced when a purchase is deleted.
     */
    public function test_raw_material_stock_reduces_when_purchase_deleted()
    {
        // Create test data
        $rawMaterial = RawMaterial::factory()->create();
        $supplier = Supplier::factory()->create();

        // Create purchase
        $purchase = RawMaterialPurchase::create([
            'raw_material_id' => $rawMaterial->id,
            'supplier_id' => $supplier->id,
            'invoice_number' => 'INV-003',
            'quantity' => 30.0,
            'total_amount' => 300.00,
            'payment_status' => 'pending',
            'amount_paid' => 0,
        ]);

        // Verify stock was created
        $stock = RawMaterialStock::where('raw_material_id', $rawMaterial->id)->first();
        $this->assertEquals(30.0, $stock->quantity_available);

        // Delete the purchase
        $purchase->delete();

        // Verify stock was reduced back to 0
        $stock->refresh();
        $this->assertEquals(0, $stock->quantity_available);
    }

    /**
     * Test multiple purchases for the same raw material accumulate stock correctly.
     */
    public function test_multiple_purchases_accumulate_stock_correctly()
    {
        // Create test data
        $rawMaterial = RawMaterial::factory()->create();
        $supplier = Supplier::factory()->create();

        // Create first purchase
        RawMaterialPurchase::create([
            'raw_material_id' => $rawMaterial->id,
            'supplier_id' => $supplier->id,
            'invoice_number' => 'INV-004',
            'quantity' => 25.0,
            'total_amount' => 250.00,
            'payment_status' => 'pending',
            'amount_paid' => 0,
        ]);

        // Verify first stock
        $stock = RawMaterialStock::where('raw_material_id', $rawMaterial->id)->first();
        $this->assertEquals(25.0, $stock->quantity_available);

        // Create second purchase
        RawMaterialPurchase::create([
            'raw_material_id' => $rawMaterial->id,
            'supplier_id' => $supplier->id,
            'invoice_number' => 'INV-005',
            'quantity' => 35.0,
            'total_amount' => 350.00,
            'payment_status' => 'pending',
            'amount_paid' => 0,
        ]);

        // Verify stock accumulated correctly (25 + 35 = 60)
        $stock->refresh();
        $this->assertEquals(60.0, $stock->quantity_available);
    }
}