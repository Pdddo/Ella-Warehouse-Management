<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionFlowTest extends TestCase
{
    use RefreshDatabase;

    // Skenario: Barang Masuk (Incoming) menambah stok
    public function test_incoming_transaction_flow()
    {
        // 1. ARRANGE
        $staff = User::factory()->create(['role' => 'staff']);
        $manager = User::factory()->create(['role' => 'manager']);
        $supplier = User::factory()->create(['role' => 'supplier']);
        
        // Stok awal 10
        $product = Product::factory()->create(['stock' => 10]);

        // 2. ACT (Staff buat Request Barang Masuk +5)
        $this->actingAs($staff)->post(route('transactions.store.incoming'), [
            'supplier_id' => $supplier->id,
            'transaction_date' => now(),
            'notes' => 'Restock rutin',
            'products' => [
                ['id' => $product->id, 'quantity' => 5]
            ]
        ]);

        // Cek 1: Stok harus MASIH 10 (karena status masih pending)
        $this->assertEquals(10, $product->fresh()->stock);

        // Ambil transaksi yang baru dibuat
        $transaction = Transaction::first();

        // 3. ACT (Manager Approve)
        $this->actingAs($manager)
             ->post(route('transactions.approve', $transaction));

        // 4. ASSERT (Stok harus jadi 15)
        $this->assertEquals(15, $product->fresh()->stock);
        $this->assertEquals('completed', $transaction->fresh()->status);
    }

    // Skenario: Barang Keluar (Outgoing) mengurangi stok
    public function test_outgoing_transaction_decreases_stock_on_approval()
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $manager = User::factory()->create(['role' => 'manager']);
        
        // Stok awal 20
        $product = Product::factory()->create(['stock' => 20]);

        // Staff request keluar 5 item
        $this->actingAs($staff)->post(route('transactions.store.outgoing'), [
            'transaction_date' => now(),
            'customer_name' => 'Customer A',
            'products' => [
                ['id' => $product->id, 'quantity' => 5]
            ]
        ]);

        // Stok masih aman (20)
        $this->assertEquals(20, $product->fresh()->stock);

        $transaction = Transaction::where('type', 'outgoing')->first();

        // Manager Approve
        $this->actingAs($manager)
             ->post(route('transactions.approve', $transaction));

        // Stok harus jadi 15 (20 - 5)
        $this->assertEquals(15, $product->fresh()->stock);
    }

    // Skenario: Validasi Stok Tidak Cukup
    public function test_cannot_create_outgoing_transaction_if_stock_insufficient()
    {
        $staff = User::factory()->create(['role' => 'staff']);
        
        // Stok cuma 2
        $product = Product::factory()->create(['stock' => 2]);

        // Staff maksa minta keluar 10
        $response = $this->actingAs($staff)->post(route('transactions.store.outgoing'), [
            'transaction_date' => now(),
            'customer_name' => 'Customer Greedy',
            'products' => [
                ['id' => $product->id, 'quantity' => 10]
            ]
        ]);

        // Harus error / kembali ke halaman sebelumnya
        $response->assertSessionHas('error'); 
        
        // Tidak ada transaksi yang tercipta
        $this->assertDatabaseCount('transactions', 0);
    }
}