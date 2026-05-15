<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
use App\Models\Product;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    private function createCategory(string $slug = 'test-cat'): Category
    {
        return Category::create([
            'name' => 'Test Catégorie',
            'slug' => $slug,
            'icon' => 'test',
        ]);
    }

    private function createProduct(Category $category, string $slug = 'test-produit'): Product
    {
        return Product::create([
            'category_id' => $category->id,
            'name'        => 'Produit Test',
            'slug'        => $slug,
            'price'       => 500,
            'stock'       => 10,
            'is_active'   => true,
        ]);
    }

    // ===== HEALTH =====
    public function test_health_endpoint()
    {
        $response = $this->getJson('/api/health');
        $response->assertStatus(200)
                 ->assertJson(['status' => 'ok']);
    }

    // ===== CATEGORIES =====
    public function test_get_categories_returns_list()
    {
        $this->createCategory('colliers');
        $this->createCategory('bagues');

        $response = $this->getJson('/api/categories');
        $response->assertStatus(200)
                 ->assertJson(['success' => true])
                 ->assertJsonCount(2, 'data');
    }

    // ===== PRODUCTS =====
    public function test_get_products_returns_list()
    {
        $category = $this->createCategory();
        $this->createProduct($category);

        $response = $this->getJson('/api/products');
        $response->assertStatus(200)
                 ->assertJson(['success' => true])
                 ->assertJsonPath('data.data.0.slug', 'test-produit');
    }

    public function test_get_product_by_slug()
    {
        $category = $this->createCategory();
        $this->createProduct($category, 'collier-saphir');

        $response = $this->getJson('/api/products/collier-saphir');
        $response->assertStatus(200)
                 ->assertJson(['success' => true])
                 ->assertJsonPath('data.slug', 'collier-saphir');
    }

    public function test_get_product_not_found()
    {
        $response = $this->getJson('/api/products/inexistant');
        $response->assertStatus(404);
    }

    public function test_filter_products_by_category()
    {
        $cat1 = $this->createCategory('colliers');
        $cat2 = $this->createCategory('bagues');
        $this->createProduct($cat1, 'collier-1');
        $this->createProduct($cat2, 'bague-1');

        $response = $this->getJson('/api/products?category=colliers');
        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data.data'));
    }

    // ===== ORDERS =====
    public function test_create_order_successfully()
    {
        $category = $this->createCategory();
        $product  = $this->createProduct($category);

        $response = $this->postJson('/api/orders', [
            'customer_name' => 'Fatima Test',
            'email'         => 'fatima@test.com',
            'phone'         => '0600000000',
            'city'          => 'Casablanca',
            'address'       => '12 Rue Test',
            'items'         => [
                ['product_id' => $product->id, 'quantity' => 2],
            ],
        ]);

        $response->assertStatus(201)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('orders', ['email' => 'fatima@test.com']);
        $this->assertEquals(8, Product::find($product->id)->stock); // 10 - 2
    }

    public function test_order_fails_with_missing_fields()
    {
        $response = $this->postJson('/api/orders', []);
        $response->assertStatus(422);
    }

    public function test_order_fails_when_stock_insufficient()
    {
        $category = $this->createCategory();
        $product  = Product::create([
            'category_id' => $category->id,
            'name'        => 'Produit Rare',
            'slug'        => 'produit-rare',
            'price'       => 500,
            'stock'       => 1,
            'is_active'   => true,
        ]);

        $response = $this->postJson('/api/orders', [
            'customer_name' => 'Test',
            'email'         => 'test@test.com',
            'phone'         => '0600000000',
            'city'          => 'Fes',
            'address'       => 'Rue X',
            'items'         => [
                ['product_id' => $product->id, 'quantity' => 99],
            ],
        ]);

        $response->assertStatus(422);
    }

    // ===== CONTACT =====
    public function test_send_contact_message()
    {
        $response = $this->postJson('/api/contact', [
            'name'    => 'Utilisateur Test',
            'email'   => 'user@test.com',
            'subject' => 'Question',
            'message' => 'Bonjour, j\'ai une question.',
        ]);

        $response->assertStatus(201)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('contact_messages', ['email' => 'user@test.com']);
    }

    public function test_contact_fails_with_short_message()
    {
        $response = $this->postJson('/api/contact', [
            'name'    => 'Test',
            'email'   => 'test@test.com',
            'message' => 'Hi', // moins de 5 caractères
        ]);

        $response->assertStatus(422);
    }

    // ===== FAVORITES =====
    public function test_toggle_favorite_add()
    {
        $category = $this->createCategory();
        $product  = $this->createProduct($category);

        $response = $this->postJson('/api/favorites/toggle', [
            'client_token' => 'token-test-001',
            'product_id'   => $product->id,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['is_favorite' => true]);

        $this->assertDatabaseHas('favorites', ['client_token' => 'token-test-001']);
    }

    public function test_toggle_favorite_remove()
    {
        $category = $this->createCategory();
        $product  = $this->createProduct($category);

        // Ajouter d'abord
        $this->postJson('/api/favorites/toggle', [
            'client_token' => 'token-test-002',
            'product_id'   => $product->id,
        ]);

        // Puis retirer
        $response = $this->postJson('/api/favorites/toggle', [
            'client_token' => 'token-test-002',
            'product_id'   => $product->id,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['is_favorite' => false]);

        $this->assertDatabaseMissing('favorites', ['client_token' => 'token-test-002']);
    }

    public function test_get_favorites_by_token()
    {
        $category = $this->createCategory();
        $product  = $this->createProduct($category);

        $this->postJson('/api/favorites/toggle', [
            'client_token' => 'token-test-003',
            'product_id'   => $product->id,
        ]);

        $response = $this->getJson('/api/favorites?client_token=token-test-003');
        $response->assertStatus(200)
                 ->assertJson(['success' => true])
                 ->assertJsonCount(1, 'data');
    }
}
