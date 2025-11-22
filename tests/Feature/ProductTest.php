<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase; // Reset database setiap kali test jalan

    // Skenario: Admin berhasil membuat produk baru
    public function test_admin_can_create_product()
    {
        // 1. ARRANGE: Siapkan user Admin dan Kategori
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();

        // 2. ACT: Admin login dan kirim data produk
        $response = $this->actingAs($admin)
                         ->post(route('products.store'), [
                             'name' => 'Produk Test',
                             'sku' => 'SKU-TEST-001', // Nanti akan di-override controller tapi input tetap butuh validasi jika ada
                             'category_id' => $category->id,
                             'description' => 'Deskripsi test',
                             'buy_price' => 5000,
                             'sell_price' => 7000,
                             'stock' => 10,
                             'min_stock' => 5,
                             'unit' => 'pcs',
                             'rack_location' => 'A-1'
                         ]);

        // 3. ASSERT: Pastikan redirect ke index dan data ada di DB
        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'Produk Test',
            'stock' => 10
        ]);
    }

    // Skenario: Admin mengupdate stok produk
    public function test_admin_can_update_product()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create(['stock' => 10]);

        $response = $this->actingAs($admin)
                         ->put(route('products.update', $product), [
                             'name' => 'Nama Baru',
                             'category_id' => $product->category_id,
                             'buy_price' => $product->buy_price,
                             'sell_price' => $product->sell_price,
                             'stock' => 20, // Stok diubah jadi 20
                             'min_stock' => $product->min_stock,
                             'unit' => $product->unit,
                         ]);

        $response->assertRedirect(route('products.index'));
        
        // Cek database apakah stok berubah
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 20
        ]);
    }
}