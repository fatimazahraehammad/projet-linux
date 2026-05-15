<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ContactMessage;
use App\Models\Favorite;

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
        $this->assertEquals('Colliers', $category->name);
    }

    public function test_category_has_many_products()
    {
        $category = Category::create([
            'name' => 'Bagues',
            'slug' => 'bagues',
            'icon' => 'ring',
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Bague Test',
            'slug' => 'bague-test',
            'price' => 500,
            'stock' => 5,
        ]);

        $this->assertCount(1, $category->products);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $category->products);
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
            'name' => 'Bracelet Doré',
            'slug' => 'bracelet-dore',
            'price' => 520,
            'stock' => 10,
        ]);

        $this->assertDatabaseHas('products', ['slug' => 'bracelet-dore']);
        $this->assertEquals(520, $product->price);
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
            'price' => 890,
            'stock' => 8,
        ]);

        $this->assertEquals($category->id, $product->category->id);
        $this->assertInstanceOf(Category::class, $product->category);
    }

    // ===== ORDER =====
    public function test_order_can_be_created()
    {
        $order = Order::create([
            'customer_name' => 'Fatima Test',
            'email'         => 'fatima@test.com',
            'phone'         => '0600000000',
            'city'          => 'Casablanca',
            'address'       => '12 Rue Test',
            'total'         => 0,
            'status'        => 'pending',
        ]);

        $this->assertDatabaseHas('orders', ['email' => 'fatima@test.com']);
        $this->assertEquals('pending', $order->status);
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
            'name'        => 'Boucles Test',
            'slug'        => 'boucles-test',
            'price'       => 430,
            'stock'       => 15,
        ]);

        $order = Order::create([
            'customer_name' => 'Sara Test',
            'email'         => 'sara@test.com',
            'phone'         => '0611111111',
            'city'          => 'Rabat',
            'address'       => '5 Avenue Test',
            'total'         => 430,
            'status'        => 'pending',
        ]);

        OrderItem::create([
            'order_id'   => $order->id,
            'product_id' => $product->id,
            'quantity'   => 1,
            'unit_price' => 430,
            'subtotal'   => 430,
        ]);

        $this->assertCount(1, $order->items);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $order->items);
    }

    // ===== CONTACT MESSAGE =====
    public function test_contact_message_can_be_created()
    {
        $message = ContactMessage::create([
            'name'    => 'Test User',
            'email'   => 'test@test.com',
            'subject' => 'Test Sujet',
            'message' => 'Ceci est un message test.',
        ]);

        $this->assertDatabaseHas('contact_messages', ['email' => 'test@test.com']);
        $this->assertEquals('Test Sujet', $message->subject);
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
            'name'        => 'Collier Fav',
            'slug'        => 'collier-fav',
            'price'       => 890,
            'stock'       => 5,
        ]);

        $favorite = Favorite::create([
            'client_token' => 'token-abc-123',
            'product_id'   => $product->id,
        ]);

        $this->assertDatabaseHas('favorites', ['client_token' => 'token-abc-123']);
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
            'name'        => 'Bague Fav',
            'slug'        => 'bague-fav',
            'price'       => 650,
            'stock'       => 12,
        ]);

        $favorite = Favorite::create([
            'client_token' => 'token-xyz-456',
            'product_id'   => $product->id,
        ]);

        $this->assertEquals($product->id, $favorite->product->id);
        $this->assertInstanceOf(Product::class, $favorite->product);
    }
}
