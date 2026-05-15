<?php
namespace Database\Seeders;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Colliers', 'slug' => 'colliers', 'icon' => 'necklace'],
            ['name' => 'Bagues', 'slug' => 'bagues', 'icon' => 'ring'],
            ['name' => 'Bracelets', 'slug' => 'bracelets', 'icon' => 'bracelet'],
            ['name' => 'Boucles d\'oreilles', 'slug' => 'boucles', 'icon' => 'earrings'],
        ];
        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
        $colliers = Category::where('slug', 'colliers')->first();
        $bagues = Category::where('slug', 'bagues')->first();
        $bracelets = Category::where('slug', 'bracelets')->first();
        $boucles = Category::where('slug', 'boucles')->first();
        $products = [
            [
                'category_id' => $colliers->id,
                'name' => 'Collier Saphir Doré',
                'description' => 'Collier artisanal marocain en finition dorée avec pierre bleue.',
                'price' => 890,
                'image' => '/images/products/collier-saphir.jpg',
                'material' => 'Or et vermeil',
                'origin' => 'Maroc',
                'stock' => 8,
                'badge' => 'Nouveau',
            ],
            [
                'category_id' => $bagues->id,
                'name' => 'Bague Éternité',
                'description' => 'Bague élégante inspirée du bijou traditionnel marocain.',
                'price' => 650,
                'image' => '/images/products/bague-eternite.jpg',
                'material' => 'Argent',
                'origin' => 'Maroc',
                'stock' => 12,
                'badge' => 'Nouveau',
            ],
            [
                'category_id' => $bracelets->id,
                'name' => 'Bracelet Torsadé',
                'description' => 'Bracelet doré torsadé fabriqué à la main.',
                'price' => 520,
                'image' => '/images/products/bracelet-torsade.jpg',
                'material' => 'Doré',
                'origin' => 'Maroc',
                'stock' => 10,
                'discount_percentage' => 15,
            ],
            [
                'category_id' => $boucles->id,
                'name' => 'Boucles Perle Blanche',
                'description' => 'Boucles d\'oreilles raffinées avec perle blanche.',
                'price' => 430,
                'image' => '/images/products/boucles-perle.jpg',
                'material' => 'Perle',
                'origin' => 'Maroc',
                'stock' => 15,
            ],
        ];
        foreach ($products as $product) {
            Product::updateOrCreate(
                ['slug' => Str::slug($product['name'])],
                array_merge($product, [
                    'slug' => Str::slug($product['name']),
                    'is_active' => true,
                ])
            );
        }
    }
}