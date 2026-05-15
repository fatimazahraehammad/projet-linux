<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ContactMessage;
use App\Models\Favorite;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    // Helper : crée une catégorie + un produit actif
    private function createProduct(array $overrides = []): Product
    {
        $category = Category::create([
            'name' => 'Colliers',
            'slug' => 'colliers-' . uniqid(),
            'icon' => 'necklace',
        ]);

        return Product::create(array_merge([
            'category_id' => $category->id,
            'name'        => 'Produit Test',
            'slug'        => 'produit-test-' . uniqid(),
            'price'       => 500,
            'stock'       => 10,
            'is_active'   => true,
        ], $overrides));
    }

    // ===== HEALTH =====
    public function test_health_endpoint_returns_ok()
    {
        $response = $this->getJson('/api/health');

        $response->assertStatus(200)
                 ->assertJson(['status' => 'ok']);
    }

    // ===== CATEGORIES =====
    public function test_get_categories_returns_list()
    {
        Category::create(['name' => 'Bagues', 'slug' => 'bagues', 'icon' => 'ring']);

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [['id', 'name', 'slug']],
                 ]);
    }

    public function test_get_categories_returns_empty_when_none()
    {
        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'data' => []]);
    }

    // ===== PRODUCTS =====
    public function test_get_products_returns_paginated_list()
    {
        $this->createProduct();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => ['data', 'total', 'per_page'],
                 ]);
    }

    public function test_get_products_filtered_by_category()
    {
        $category = Category::create(['name' => 'Bracelets', 'slug' => 'bracelets', 'icon' => 'bracelet']);
        Product::create([
            'category_id' => $category->id,
            'name'        => 'Bracelet Doré',
            'slug'        => 'bracelet-dore',
            'price'       => 300,
            'stock'       => 5,
            'is_active'   => true,
        ]);

        $response = $this->getJson('/api/products?category=bracelets');

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertCount(1, $response->json('data.data'));
    }

    public function test_get_products_filtered_by_search()
    {
        $this->createProduct(['name' => 'Collier Unique', 'slug' => 'collier-unique']);
        $this->createProduct(['name' => 'Bague Simple', 'slug' => 'bague-simple']);

        $response = $this->getJson('/api/products?search=Collier');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data.data'));
    }

    public function test_get_products_sorted_by_price_asc()
    {
        $category = Category::create(['name' => 'Test', 'slug' => 'test-sort', 'icon' => 'icon']);
        Product::create(['category_id' => $category->id, 'name' => 'Cher', 'slug' => 'cher', 'price' => 900, 'stock' => 1, 'is_active' => true]);
        Product::create(['category_id' => $category->id, 'name' => 'Pas cher', 'slug' => 'pas-cher', 'price' => 100, 'stock' => 1, 'is_active' => true]);

        $response = $this->getJson('/api/products?sort=price_asc');

        $response->assertStatus(200);
        $items = $response->json('data.data');
        $this->assertLessThanOrEqual($items[1]['price'], $items[0]['price']);
    }

    public function test_get_product_by_slug()
    {
        $product = $this->createProduct(['name' => 'Collier Test', 'slug' => 'collier-test-show']);

        $response = $this->getJson('/api/products/collier-test-show');

        $response->assertStatus(200)
                 ->assertJsonPath('data.slug', 'collier-test-show');
    }

    public function test_get_product_by_slug_returns_404_if_not_found()
    {
        $response = $this->getJson('/api/products/slug-inexistant');

        $response->assertStatus(404);
    }

    public function test_inactive_product_not_returned()
    {
        $this->createProduct(['name' => 'Inactif', 'slug' => 'inactif', 'is_active' => false]);

        $response = $this->getJson('/api/products/inactif');

        $response->assertStatus(404);
    }

    // ===== ORDERS =====
    public function test_create_order_successfully()
    {
        $product = $this->createProduct(['price' => 400, 'stock' => 10]);

        $response = $this->postJson('/api/orders', [
            'customer_name' => 'Fatima Test',
            'email'         => 'fatima@test.com',
            'phone'         => '0600000000',
            'city'          => 'Casablanca',
            'address'       => '123 Rue Test',
            'items'         => [
                ['product_id' => $product->id, 'quantity' => 2],
            ],
        ]);

        $response->assertStatus(201)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('orders', ['email' => 'fatima@test.com']);
        $this->assertEquals(8, $product->fresh()->stock); // stock décrémenté
    }

    public function test_create_order_fails_with_insufficient_stock()
    {
        $product = $this->createProduct(['price' => 400, 'stock' => 1]);

        $response = $this->postJson('/api/orders', [
            'customer_name' => 'Test Client',
            'email'         => 'client@test.com',
            'phone'         => '0600000001',
            'city'          => 'Rabat',
            'address'       => '456 Rue Test',
            'items'         => [
                ['product_id' => $product->id, 'quantity' => 5],
            ],
        ]);

        $response->assertStatus(422);
    }

    public function test_create_order_fails_with_missing_fields()
    {
        $response = $this->postJson('/api/orders', [
            'email' => 'test@test.com',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['customer_name', 'phone', 'city', 'address', 'items']);
    }

    public function test_create_order_fails_with_invalid_product()
    {
        $response = $this->postJson('/api/orders', [
            'customer_name' => 'Test',
            'email'         => 'test@test.com',
            'phone'         => '0600000000',
            'city'          => 'Casablanca',
            'address'       => '123 Rue',
            'items'         => [
                ['product_id' => 9999, 'quantity' => 1],
            ],
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['items.0.product_id']);
    }

    // ===== CONTACT =====
    public function test_send_contact_message_successfully()
    {
        $response = $this->postJson('/api/contact', [
            'name'    => 'Test User',
            'email'   => 'test@test.com',
            'subject' => 'Test Sujet',
            'message' => 'Ceci est un message de test.',
        ]);

        $response->assertStatus(201)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('contact_messages', ['email' => 'test@test.com']);
    }

    public function test_send_contact_message_fails_with_missing_fields()
    {
        $response = $this->postJson('/api/contact', [
            'email' => 'test@test.com',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'message']);
    }

    public function test_send_contact_message_fails_with_short_message()
    {
        $response = $this->postJson('/api/contact', [
            'name'    => 'Test',
            'email'   => 'test@test.com',
            'message' => 'Hi', // moins de 5 caractères
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['message']);
    }

    // ===== FAVORITES =====
    public function test_toggle_favorite_adds_product()
    {
        $product = $this->createProduct();

        $response = $this->postJson('/api/favorites/toggle', [
            'client_token' => 'token-abc',
            'product_id'   => $product->id,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'is_favorite' => true]);

        $this->assertDatabaseHas('favorites', [
            'client_token' => 'token-abc',
            'product_id'   => $product->id,
        ]);
    }

    public function test_toggle_favorite_removes_product()
    {
        $product = $this->createProduct();

        Favorite::create(['client_token' => 'token-abc', 'product_id' => $product->id]);

        $response = $this->postJson('/api/favorites/toggle', [
            'client_token' => 'token-abc',
            'product_id'   => $product->id,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'is_favorite' => false]);

        $this->assertDatabaseMissing('favorites', [
            'client_token' => 'token-abc',
            'product_id'   => $product->id,
        ]);
    }

    public function test_get_favorites_by_token()
    {
        $product = $this->createProduct();
        Favorite::create(['client_token' => 'token-xyz', 'product_id' => $product->id]);

        $response = $this->getJson('/api/favorites?client_token=token-xyz');

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertCount(1, $response->json('data'));
    }

    public function test_get_favorites_fails_without_token()
    {
        $response = $this->getJson('/api/favorites');

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['client_token']);
    }

    public function test_toggle_favorite_fails_with_invalid_product()
    {
        $response = $this->postJson('/api/favorites/toggle', [
            'client_token' => 'token-abc',
            'product_id'   => 9999,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['product_id']);
    }
}
