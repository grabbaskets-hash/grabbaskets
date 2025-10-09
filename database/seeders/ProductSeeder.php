<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\User;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create the default seller
        $seller = User::where('email', 'seller@example.com')->first();
        if (!$seller) {
            $seller = User::create([
                'name' => 'Default Seller',
                'email' => 'seller@example.com',
                'phone' => '9876543210',
                'billing_address' => 'Default Address',
                'state' => 'Default State',
                'city' => 'Default City',
                'pincode' => '123456',
                'role' => 'seller',
                'sex' => 'male',
                'password' => bcrypt('password'),
            ]);
        }
        $sellerId = $seller->id;

        // Get or create the demo seller
        $demoSeller = User::where('email', 'demo-seller@example.com')->first();
        if (!$demoSeller) {
            $demoSeller = User::create([
                'name' => 'Demo Seller',
                'email' => 'demo-seller@example.com',
                'phone' => '9000000000',
                'billing_address' => 'Demo Address',
                'state' => 'Demo State',
                'city' => 'Demo City',
                'pincode' => '999999',
                'role' => 'seller',
                'sex' => 'male',
                'password' => bcrypt('password'),
            ]);
        }
        $demoSellerId = $demoSeller->id;

        // Seed products for default seller
        $this->createElectronicsProducts($sellerId);
        $this->createFashionProducts($sellerId);
        $this->createHomeKitchenProducts($sellerId);
        $this->createBeautyProducts($sellerId);
        $this->createSportsProducts($sellerId);
        $this->createBooksProducts($sellerId);
        $this->createOtherProducts($sellerId);

        // Seed demo products for demo seller (with DEMO- prefix for unique_id)
        $this->createElectronicsProducts($demoSellerId, 'DEMO-');
        $this->createFashionProducts($demoSellerId, 'DEMO-');
        $this->createHomeKitchenProducts($demoSellerId, 'DEMO-');
        $this->createBeautyProducts($demoSellerId, 'DEMO-');
        $this->createSportsProducts($demoSellerId, 'DEMO-');
        $this->createBooksProducts($demoSellerId, 'DEMO-');
        $this->createOtherProducts($demoSellerId, 'DEMO-');

        $this->command->info('Products created successfully for default and demo sellers!');
    }

    private function createElectronicsProducts($sellerId, $uniquePrefix = '')
    {
        $electronics = Category::where('name', 'ELECTRONICS')->first();
        $mobileSubcat = Subcategory::where('name', 'Mobile Phones')->first();
        $laptopSubcat = Subcategory::where('name', 'Laptops & Computers')->first();
        $audioSubcat = Subcategory::where('name', 'Audio & Headphones')->first();

    $products = [
            // Mobile Phones
            [
                'name' => 'iPhone 15 Pro Max',
                'unique_id' => 'IP1',
                'description' => 'Latest iPhone with titanium design, A17 Pro chip, and advanced camera system',
                'price' => 159900.00,
                'stock' => 25,
                'category_id' => $electronics->id,
                'subcategory_id' => $mobileSubcat->id,
                'seller_id' => $sellerId,
                'gift_option' => 'yes',
                'delivery_charge' => 0,
                'discount' => 5,
                'image' => 'products/default-electronics.jpg',
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'unique_id' => 'SG1',
                'description' => 'Premium Android smartphone with S Pen and 200MP camera',
                'price' => 134999.00,
                'stock' => 30,
                'category_id' => $electronics->id,
                'subcategory_id' => $mobileSubcat->id,
                'seller_id' => $sellerId,
                'gift_option' => 'yes',
                'delivery_charge' => 0,
                'discount' => 8,
                'image' => 'products/default-electronics.jpg',
            ],
            [
                'name' => 'OnePlus 12',
                'unique_id' => 'OP1',
                'description' => 'Flagship Android phone with Snapdragon 8 Gen 3 and 120Hz display',
                'price' => 64999.00,
                'stock' => 40,
                'category_id' => $electronics->id,
                'subcategory_id' => $mobileSubcat->id,
                'seller_id' => $sellerId,
                'gift_option' => 'yes',
                'delivery_charge' => 199,
                'discount' => 12,
                'image' => 'products/default-electronics.jpg',
            ],
            
            // Laptops
            [
                'name' => 'MacBook Air M3',
                'unique_id' => 'MB1',
                'description' => 'Ultra-thin laptop with Apple M3 chip and all-day battery life',
                'price' => 114900.00,
                'stock' => 20,
                'category_id' => $electronics->id,
                'subcategory_id' => $laptopSubcat->id,
                'seller_id' => $sellerId,
                'gift_option' => 'yes',
                'delivery_charge' => 0,
                'discount' => 10,
                'image' => 'products/default-electronics.jpg',
            ],
            [
                'name' => 'Dell XPS 13',
                'unique_id' => 'DX1',
                'description' => 'Premium ultrabook with Intel Core i7 and InfinityEdge display',
                'price' => 89999.00,
                'stock' => 15,
                'category_id' => $electronics->id,
                'subcategory_id' => $laptopSubcat->id,
                'seller_id' => $sellerId,
                'gift_option' => 'yes',
                'delivery_charge' => 499,
                'discount' => 15,
                'image' => 'products/default-electronics.jpg',
            ],

            // Audio
            [
                'name' => 'Sony WH-1000XM5',
                'unique_id' => 'SH1',
                'description' => 'Industry-leading noise canceling wireless headphones',
                'price' => 29990.00,
                'stock' => 50,
                'category_id' => $electronics->id,
                'subcategory_id' => $audioSubcat->id,
                'seller_id' => $sellerId,
                'gift_option' => 'yes',
                'delivery_charge' => 0,
                'discount' => 20,
                'image' => 'products/default-electronics.jpg',
            ],
            [
                'name' => 'AirPods Pro 2nd Gen',
                'unique_id' => 'AP1',
                'description' => 'Apple AirPods Pro with adaptive transparency and spatial audio',
                'price' => 24900.00,
                'stock' => 75,
                'category_id' => $electronics->id,
                'subcategory_id' => $audioSubcat->id,
                'seller_id' => $sellerId,
                'gift_option' => 'yes',
                'delivery_charge' => 0,
                'discount' => 8,
                'image' => 'products/default-electronics.jpg',
            ],
        ];

        foreach ($products as $product) {
            $productCopy = $product;
            if ($uniquePrefix) {
                $productCopy['unique_id'] = $uniquePrefix . $product['unique_id'];
            }
            $productCopy['seller_id'] = $sellerId;
            Product::firstOrCreate(
                ['unique_id' => $productCopy['unique_id']],
                $productCopy
            );
        }
    }

    private function createFashionProducts($sellerId, $uniquePrefix = '')
    {

        $mensFashion = Category::where('name', "MEN'S FASHION")->first();
        $womensFashion = Category::where('name', "WOMEN'S FASHION")->first();
        $menSubcat = Subcategory::where('name', "Men's Shirts")->first();
        $womenSubcat = Subcategory::where('name', "Women's Dresses")->first();
        $footwearSubcat = Subcategory::where('name', "Men's Shoes")->first();

        if (!$mensFashion) {
            echo "[ERROR] Category 'MEN'S FASHION' not found. Please check CategorySeeder.\n";
            return;
        }
        if (!$womensFashion) {
            echo "[ERROR] Category 'WOMEN'S FASHION' not found. Please check CategorySeeder.\n";
            return;
        }
        if (!$menSubcat) {
            echo "[ERROR] Subcategory 'Men's Shirts' not found. Please check SubcategorySeeder.\n";
            return;
        }
        if (!$womenSubcat) {
            echo "[ERROR] Subcategory 'Women's Dresses' not found. Please check SubcategorySeeder.\n";
            return;
        }
        if (!$footwearSubcat) {
            echo "[ERROR] Subcategory 'Men's Shoes' not found. Please check SubcategorySeeder.\n";
            return;
        }

    $products = [
        // Men's Fashion (Shirts)
        [
            'name' => "Levi's 501 Original Jeans",
            'unique_id' => 'LJ1',
            'description' => 'Classic straight-leg jeans in premium denim',
            'price' => 4999.00,
            'stock' => 100,
            'category_id' => $mensFashion->id,
            'subcategory_id' => $menSubcat->id,
            'seller_id' => $sellerId,
            'gift_option' => 'yes',
            'delivery_charge' => 99,
            'discount' => 25,
            'image' => 'products/default-fashion.jpg',
        ],
        [
            'name' => 'Ralph Lauren Polo Shirt',
            'unique_id' => 'RL1',
            'description' => 'Classic cotton polo shirt with iconic logo',
            'price' => 3499.00,
            'stock' => 80,
            'category_id' => $mensFashion->id,
            'subcategory_id' => $menSubcat->id,
            'seller_id' => $sellerId,
            'gift_option' => 'yes',
            'delivery_charge' => 99,
            'discount' => 15,
            'image' => 'products/default-fashion.jpg',
        ],

        // Women's Fashion (Dresses)
        [
            'name' => 'Zara Floral Dress',
            'unique_id' => 'ZD1',
            'description' => 'Elegant floral print dress perfect for any occasion',
            'price' => 2999.00,
            'stock' => 60,
            'category_id' => $womensFashion->id,
            'subcategory_id' => $womenSubcat->id,
            'seller_id' => $sellerId,
            'gift_option' => 'yes',
            'delivery_charge' => 99,
            'discount' => 30,
            'image' => 'products/default-fashion.jpg',
        ],

        // Men's Fashion (Shoes)
        [
            'name' => 'Nike Air Jordan 1',
            'unique_id' => 'NJ1',
            'description' => 'Iconic basketball shoes with premium leather construction',
            'price' => 12995.00,
            'stock' => 45,
            'category_id' => $mensFashion->id,
            'subcategory_id' => $footwearSubcat->id,
            'seller_id' => $sellerId,
            'gift_option' => 'no',
            'delivery_charge' => 199,
            'discount' => 18,
            'image' => 'products/default-fashion.jpg',
        ],
        [
            'name' => 'Adidas Ultraboost 22',
            'unique_id' => 'AU1',
            'description' => 'Premium running shoes with responsive cushioning',
            'price' => 16999.00,
            'stock' => 35,
            'category_id' => $mensFashion->id,
            'subcategory_id' => $footwearSubcat->id,
            'seller_id' => $sellerId,
            'gift_option' => 'yes',
            'delivery_charge' => 199,
            'discount' => 22,
            'image' => 'products/default-fashion.jpg',
        ],
    ];

        foreach ($products as $product) {
            $productCopy = $product;
            if ($uniquePrefix) {
                $productCopy['unique_id'] = $uniquePrefix . $product['unique_id'];
            }
            $productCopy['seller_id'] = $sellerId;
            Product::firstOrCreate(
                ['unique_id' => $productCopy['unique_id']],
                $productCopy
            );
        }
    }

    private function createHomeKitchenProducts($sellerId, $uniquePrefix = '')
    {
        $home = Category::where('name', 'HOME & KITCHEN')->first();
        $kitchenSubcat = Subcategory::where('name', 'Kitchen Appliances')->first();
        $decorSubcat = Subcategory::where('name', 'Home Decor')->first();

    $products = [
            [
                'name' => 'Dyson V15 Detect Vacuum',
                'unique_id' => 'DV1',
                'description' => 'Powerful cordless vacuum with laser dust detection',
                'price' => 59900.00,
                'stock' => 15,
                'category_id' => $home->id,
                'subcategory_id' => $kitchenSubcat->id,
                'seller_id' => $sellerId,
                'gift_option' => 'yes',
                'delivery_charge' => 0,
                'discount' => 12,
                'image' => 'products/default-home.jpg',
            ],
            [
                'name' => 'KitchenAid Stand Mixer',
                'unique_id' => 'KA1',
                'description' => 'Professional 5-quart stand mixer for baking enthusiasts',
                'price' => 45999.00,
                'stock' => 20,
                'category_id' => $home->id,
                'subcategory_id' => $kitchenSubcat->id,
                'seller_id' => $sellerId,
                'gift_option' => 'yes',
                'delivery_charge' => 299,
                'discount' => 8,
                'image' => 'products/default-home.jpg',
            ],
            [
                'name' => 'Decorative Wall Mirror',
                'unique_id' => 'DM1',
                'description' => 'Elegant round mirror with gold frame for home decor',
                'price' => 3999.00,
                'stock' => 40,
                'category_id' => $home->id,
                'subcategory_id' => $decorSubcat->id,
                'seller_id' => $sellerId,
                'gift_option' => 'yes',
                'delivery_charge' => 199,
                'discount' => 25,
                'image' => 'products/default-home.jpg',
            ],
        ];

        foreach ($products as $product) {
            $productCopy = $product;
            if ($uniquePrefix) {
                $productCopy['unique_id'] = $uniquePrefix . $product['unique_id'];
            }
            $productCopy['seller_id'] = $sellerId;
            Product::firstOrCreate(
                ['unique_id' => $productCopy['unique_id']],
                $productCopy
            );
        }
    }

    private function createBeautyProducts($sellerId, $uniquePrefix = '')
    {
        $beauty = Category::where('name', 'BEAUTY & PERSONAL CARE')->first();
        $skincareSubcat = Subcategory::where('name', 'Skincare')->first();
        $makeupSubcat = Subcategory::where('name', 'Makeup')->first();

    $products = [
            [
                'name' => 'The Ordinary Niacinamide Serum',
                'unique_id' => 'TO1',
                'description' => 'High-strength vitamin and zinc serum for blemish control',
                'price' => 899.00,
                'stock' => 200,
                'category_id' => $beauty->id,
                'subcategory_id' => $skincareSubcat->id,
                'seller_id' => $sellerId,
                'gift_option' => 'yes',
                'delivery_charge' => 49,
                'discount' => 0,
                'image' => 'products/default-beauty.jpg',
            ],
            [
                'name' => 'MAC Ruby Woo Lipstick',
                'unique_id' => 'MC1',
                'description' => 'Iconic red matte lipstick with long-lasting formula',
                'price' => 1999.00,
                'stock' => 150,
                'category_id' => $beauty->id,
                'subcategory_id' => $makeupSubcat->id,
                'seller_id' => $sellerId,
                'gift_option' => 'yes',
                'delivery_charge' => 99,
                'discount' => 10,
                'image' => 'products/default-beauty.jpg',
            ],
        ];

        foreach ($products as $product) {
            $productCopy = $product;
            if ($uniquePrefix) {
                $productCopy['unique_id'] = $uniquePrefix . $product['unique_id'];
            }
            $productCopy['seller_id'] = $sellerId;
            Product::firstOrCreate(
                ['unique_id' => $productCopy['unique_id']],
                $productCopy
            );
        }
    }

    private function createSportsProducts($sellerId, $uniquePrefix = '')
    {
        $sports = Category::where('name', 'SPORTS & FITNESS')->first();
        $exerciseSubcat = Subcategory::where('name', 'Exercise Equipment')->first();
        $athleticSubcat = Subcategory::where('name', 'Athletic Wear')->first();

    $products = [
            [
                'name' => 'Adjustable Dumbbell Set',
                'unique_id' => 'AD1',
                'description' => 'Space-saving adjustable dumbbells for home workouts',
                'price' => 12999.00,
                'stock' => 25,
                'category_id' => $sports->id,
                'subcategory_id' => $exerciseSubcat->id,
                'seller_id' => $sellerId,
                'gift_option' => 'no',
                'delivery_charge' => 499,
                'discount' => 15,
                'image' => 'products/default-sports.jpg',
            ],
            [
                'name' => 'Nike Dri-FIT T-Shirt',
                'unique_id' => 'ND1',
                'description' => 'Moisture-wicking athletic t-shirt for optimal performance',
                'price' => 1999.00,
                'stock' => 100,
                'category_id' => $sports->id,
                'subcategory_id' => $athleticSubcat->id,
                'seller_id' => $sellerId,
                'gift_option' => 'yes',
                'delivery_charge' => 99,
                'discount' => 20,
                'image' => 'products/default-sports.jpg',
            ],
        ];

        foreach ($products as $product) {
            $productCopy = $product;
            if ($uniquePrefix) {
                $productCopy['unique_id'] = $uniquePrefix . $product['unique_id'];
            }
            $productCopy['seller_id'] = $sellerId;
            Product::firstOrCreate(
                ['unique_id' => $productCopy['unique_id']],
                $productCopy
            );
        }
    }

    private function createBooksProducts($sellerId, $uniquePrefix = '')
    {
        $books = Category::where('name', 'BOOKS & EDUCATION')->first();
        $fictionSubcat = Subcategory::where('name', 'Fiction')->first();
        $nonfictionSubcat = Subcategory::where('name', 'Non-Fiction')->first();

    $products = [
            [
                'name' => 'The Psychology of Money',
                'unique_id' => 'PM1',
                'description' => 'Timeless lessons on wealth, greed, and happiness by Morgan Housel',
                'price' => 499.00,
                'stock' => 500,
                'category_id' => $books->id,
                'subcategory_id' => $nonfictionSubcat->id,
                'seller_id' => $sellerId,
                'gift_option' => 'yes',
                'delivery_charge' => 0,
                'discount' => 0,
                'image' => 'products/default-books.jpg',
            ],
            [
                'name' => 'Atomic Habits',
                'unique_id' => 'AH1',
                'description' => 'An easy and proven way to build good habits by James Clear',
                'price' => 399.00,
                'stock' => 300,
                'category_id' => $books->id,
                'subcategory_id' => $nonfictionSubcat->id,
                'seller_id' => $sellerId,
                'gift_option' => 'yes',
                'delivery_charge' => 0,
                'discount' => 5,
                'image' => 'products/default-books.jpg',
            ],
            [
                'name' => 'The Alchemist',
                'unique_id' => 'AL1',
                'description' => 'Paulo Coelho\'s masterpiece about following your dreams',
                'price' => 299.00,
                'stock' => 400,
                'category_id' => $books->id,
                'subcategory_id' => $fictionSubcat->id,
                'seller_id' => $sellerId,
                'gift_option' => 'yes',
                'delivery_charge' => 0,
                'discount' => 10,
                'image' => 'products/default-books.jpg',
            ],
        ];

        foreach ($products as $product) {
            $productCopy = $product;
            if ($uniquePrefix) {
                $productCopy['unique_id'] = $uniquePrefix . $product['unique_id'];
            }
            $productCopy['seller_id'] = $sellerId;
            Product::firstOrCreate(
                ['unique_id' => $productCopy['unique_id']],
                $productCopy
            );
        }
    }

    private function createOtherProducts($sellerId, $uniquePrefix = '')
    {
    // Toys
    $toys = Category::where('name', "KIDS & TOYS")->first();
    $educationalSubcat = Subcategory::where('name', 'Educational Toys')->first();
        
    // Health
    $health = Category::where('name', 'HEALTH & WELLNESS')->first();
    $vitaminsSubcat = Subcategory::where('name', 'Vitamins & Supplements')->first();

    if (!$toys) {
        echo "[ERROR] Category 'KIDS & TOYS' not found. Please check CategorySeeder.\n";
        return;
    }
    if (!$educationalSubcat) {
        echo "[ERROR] Subcategory 'Educational Toys' not found. Please check SubcategorySeeder.\n";
        return;
    }
    if (!$health) {
        echo "[ERROR] Category 'HEALTH & WELLNESS' not found. Please check CategorySeeder.\n";
        return;
    }
    if (!$vitaminsSubcat) {
        echo "[ERROR] Subcategory 'Vitamins & Supplements' not found. Please check SubcategorySeeder.\n";
        return;
    }
    $products = [
            [
                'name' => 'LEGO Creator 3-in-1 Set',
                'unique_id' => 'LG1',
                'description' => 'Build 3 different models with this creative LEGO set',
                'price' => 2999.00,
                'stock' => 60,
                'category_id' => $toys->id,
                'subcategory_id' => $educationalSubcat->id,
                'seller_id' => $sellerId,
                'gift_option' => 'yes',
                'delivery_charge' => 149,
                'discount' => 12,
                'image' => 'products/default-toys.jpg',
            ],
            [
                'name' => 'Multivitamin Tablets',
                'unique_id' => 'MV1',
                'description' => 'Complete daily nutrition with essential vitamins and minerals',
                'price' => 799.00,
                'stock' => 200,
                'category_id' => $health->id,
                'subcategory_id' => $vitaminsSubcat->id,
                'seller_id' => $sellerId,
                'gift_option' => 'no',
                'delivery_charge' => 99,
                'discount' => 5,
                'image' => 'products/default-health.jpg',
            ],
        ];

        foreach ($products as $product) {
            $productCopy = $product;
            if ($uniquePrefix) {
                $productCopy['unique_id'] = $uniquePrefix . $product['unique_id'];
            }
            $productCopy['seller_id'] = $sellerId;
            Product::firstOrCreate(
                ['unique_id' => $productCopy['unique_id']],
                $productCopy
            );
        }
    }
}
