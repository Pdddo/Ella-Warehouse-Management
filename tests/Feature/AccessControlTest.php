<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    // Skenario: Staff TIDAK BOLEH akses halaman tambah produk
    public function test_staff_cannot_access_product_create_page()
    {
        $staff = User::factory()->create(['role' => 'staff']);

        $response = $this->actingAs($staff)
                         ->get(route('products.create'));

        // Berdasarkan Middleware Anda, user dilempar ke dashboard dengan error
        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('error');
    }

    // Skenario: Staff TIDAK BOLEH approve transaksi
    public function test_staff_cannot_approve_transaction()
    {
        // 1. ARRANGE: Siapkan data
        $staff = User::factory()->create(['role' => 'staff']);
        $transaction = \App\Models\Transaction::factory()->create(['status' => 'pending']);

        // 2. ACT: Lakukan aksi (Bagian ini yang hilang sebelumnya)
        $response = $this->actingAs($staff)
                         ->post(route('transactions.approve', $transaction));

        // 3. ASSERT: Cek hasil
        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('error');
    }

    // Skenario: Admin BOLEH akses halaman kategori
    public function test_admin_can_access_categories()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
                         ->get(route('categories.index'));

        $response->assertStatus(200);
    }
}