<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ContactMessage;
use App\Models\Favorite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class ModelTest extends TestCase
{
    use RefreshDatabase;

    // ===== CATEGORY =====
    public function test_category_can_be_created()
    {
        $category = Category::create([
            'name' => 'Colliers',
            'slug' => 'colliers',
            'icon' => 'necklace',
        ]);

        $this->assertDatabaseHas('categories', ['slug' => 'colliers']);
    }

    public function test_category_has_many_products()
    {
        $category = Category::create([
            'name' => 'Bagues',
            'slug' => 'bagues',
            'icon' => 'ring',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Bague Test',
            'slug' => 'bague-test',
            'price' => 500,
            'stock' => 5,
        ]);

        $this->assertTrue($category->products->contains($product));
    }

    // ===== PRODUCT =====
    public function test_product_can_be_created()
    {
        $category = Category::create([
            'name' => 'Bracelets',
            'slug' => 'bracelets',
            'icon' => 'bracelet',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Bracelet Test',
            'slug' => 'bracelet-test',
            'price' => 300,
            'stock' => 10,
        ]);

        $this->assertDatabaseHas('products', ['slug' => 'bracelet-test']);
    }

    public function test_product_belongs_to_category()
    {
        $category = Category::create([
            'name' => 'Colliers',
            'slug' => 'colliers-test',
            'icon' => 'necklace',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Collier Test',
            'slug' => 'collier-test',
            'price' => 800,
            'stock' => 3,
        ]);

        $this->assertEquals($category->id, $product->category->id);
    }

    // ===== ORDER =====
    public function test_order_can_be_created()
    {
        $order = Order::create([
            'customer_name' => 'Fatima Test',
            'email' => 'fatima@test.com',
            'phone' => '0600000000',
            'city' => 'Casablanca',
            'address' => '123 Rue Test',
            'total' => 1500,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('orders', ['email' => 'fatima@test.com']);
    }

    public function test_order_has_many_items()
    {
        $category = Category::create([
            'name' => 'Boucles',
            'slug' => 'boucles',
            'icon' => 'earrings',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Boucle Test',
            'slug' => 'boucle-test',
            'price' => 400,
            'stock' => 5,
        ]);

        $order = Order::create([
            'customer_name' => 'Test Client',
            'email' => 'client@test.com',
            'phone' => '0600000001',
            'city' => 'Rabat',
            'address' => '456 Rue Test',
            'total' => 400,
            'status' => 'pending',
        ]);

        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 400,
            'subtotal' => 400,
        ]);

        $this->assertTrue($order->items->contains($item));
    }

    // ===== CONTACT MESSAGE =====
    public function test_contact_message_can_be_created()
    {
        $message = ContactMessage::create([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'subject' => 'Test Sujet',
            'message' => 'Ceci est un message test.',
        ]);

        $this->assertDatabaseHas('contact_messages', ['email' => 'test@test.com']);
    }

    // ===== FAVORITE =====
    public function test_favorite_can_be_created()
    {
        $category = Category::create([
            'name' => 'Colliers',
            'slug' => 'colliers-fav',
            'icon' => 'necklace',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Collier Favori',
            'slug' => 'collier-favori',
            'price' => 700,
            'stock' => 4,
        ]);

        $favorite = Favorite::create([
            'client_token' => 'token-test-123',
            'product_id' => $product->id,
        ]);

        $this->assertDatabaseHas('favorites', ['client_token' => 'token-test-123']);
    }

    public function test_favorite_belongs_to_product()
    {
        $category = Category::create([
            'name' => 'Bagues',
            'slug' => 'bagues-fav',
            'icon' => 'ring',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Bague Favorite',
            'slug' => 'bague-favorite',
            'price' => 600,
            'stock' => 6,
        ]);

        $favorite = Favorite::create([
            'client_token' => 'token-test-456',
            'product_id' => $product->id,
        ]);

        $this->assertEquals($product->id, $favorite->product->id);
    }
}
