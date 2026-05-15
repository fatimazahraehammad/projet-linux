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
            // COLLIERS
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
                'category_id' => $colliers->id,
                'name' => 'Collier Lune',
                'description' => 'Collier délicat avec pendentif lune en nacre, finition argent.',
                'price' => 480,
                'image' => '/images/products/collier-lune.jpg',
                'material' => 'Argent · Nacre',
                'origin' => 'Maroc',
                'stock' => 10,
            ],
            [
                'category_id' => $colliers->id,
                'name' => 'Collier Étoile',
                'description' => 'Collier fin avec pendentif étoile en or rose 14K.',
                'price' => 750,
                'image' => '/images/products/collier-etoile.jpg',
                'material' => 'Or Rose · 14K',
                'origin' => 'Maroc',
                'stock' => 6,
            ],

            // BAGUES
            [
                'category_id' => $bagues->id,
                'name' => 'Bague Éternité',
                'description' => 'Bague élégante inspirée du bijou traditionnel marocain.',
                'price' => 650,
                'image' => '/images/products/bague-eternite.jpg',
                'material' => 'Argent · Zircon',
                'origin' => 'Maroc',
                'stock' => 12,
                'badge' => 'Nouveau',
            ],
            [
                'category_id' => $bagues->id,
                'name' => 'Bague Solitaire',
                'description' => 'Bague solitaire intemporelle en or 18K avec diamant central.',
                'price' => 1200,
                'image' => '/images/products/bague-solitaire.jpg',
                'material' => 'Or 18K · Diamant',
                'origin' => 'Maroc',
                'stock' => 5,
            ],

            // BRACELETS
            [
                'category_id' => $bracelets->id,
                'name' => 'Bracelet Torsadé',
                'description' => 'Bracelet doré torsadé fabriqué à la main.',
                'price' => 520,
                'image' => '/images/products/bracelet-torsade.jpg',
                'material' => 'Or Rose · 14K',
                'origin' => 'Maroc',
                'stock' => 10,
                'discount_percentage' => 15,
            ],
            [
                'category_id' => $bracelets->id,
                'name' => 'Bracelet Jonc Doré',
                'description' => 'Bracelet jonc élégant en vermeil or 18K, style minimaliste.',
                'price' => 610,
                'image' => '/images/products/bracelet-jonc.jpg',
                'material' => 'Or 18K · Vermeil',
                'origin' => 'Maroc',
                'stock' => 7,
                'badge' => 'Nouveau',
            ],

            [
                'category_id' => $boucles->id,
                'name' => 'Boucles Soleil',
                'description' => 'Boucles d\'oreilles en forme de soleil, finition or 18K vermeil.',
                'price' => 730,
                'image' => '/images/products/boucles-soleil.jpg',
                'material' => 'Or 18K · Vermeil',
                'origin' => 'Maroc',
                'stock' => 9,
            ],
            [
                'category_id' => $boucles->id,
                'name' => 'Boucles Perle',
                'description' => 'Boucles pendantes avec perle baroque et monture en argent doré.',
                'price' => 390,
                'image' => '/images/products/boucles-perle-baroque.jpg',
                'material' => 'Argent · Perle',
                'origin' => 'Maroc',
                'stock' => 11,
                'discount_percentage' => 10,
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