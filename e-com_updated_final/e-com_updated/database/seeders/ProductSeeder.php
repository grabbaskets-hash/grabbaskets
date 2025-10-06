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
        // Get or create a seller user
        $seller = User::first();
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

        // Electronics Products
        $this->createElectronicsProducts($sellerId);
        
        // Fashion Products
        $this->createFashionProducts($sellerId);
        
        // Home & Kitchen Products
        $this->createHomeKitchenProducts($sellerId);
        
        // Beauty Products
        $this->createBeautyProducts($sellerId);
        
        // Sports Products
        $this->createSportsProducts($sellerId);
        
        // Books Products
        $this->createBooksProducts($sellerId);
        
        // Other Categories
        $this->createOtherProducts($sellerId);

        $this->command->info('Products created successfully!');
    }

    private function createElectronicsProducts($sellerId)
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
            Product::firstOrCreate(
                ['unique_id' => $product['unique_id']],
                $product
            );
        }
    }

    private function createFashionProducts($sellerId)
    {

        $fashion = Category::where('name', 'FASHION & CLOTHING')->first();
        $menSubcat = Subcategory::where('name', "Men's Clothing")->first();
        $womenSubcat = Subcategory::where('name', "Women's Clothing")->first();
        $footwearSubcat = Subcategory::where('name', 'Footwear')->first();

        if (!$fashion) {
            echo "[ERROR] Category 'FASHION & CLOTHING' not found. Please check CategorySeeder.\n";
            return;
        }
        if (!$menSubcat) {
            echo "[ERROR] Subcategory 'Men's Clothing' not found. Please check SubcategorySeeder.\n";
            return;
        }
        if (!$womenSubcat) {
            echo "[ERROR] Subcategory 'Women's Clothing' not found. Please check SubcategorySeeder.\n";
            return;
        }
        if (!$footwearSubcat) {
            echo "[ERROR] Subcategory 'Footwear' not found. Please check SubcategorySeeder.\n";
            return;
        }

    $products = [
            // Men's Clothing
            [
                'name' => 'Levi\'s 501 Original Jeans',
                'unique_id' => 'LJ1',
                'description' => 'Classic straight-leg jeans in premium denim',
                'price' => 4999.00,
                'stock' => 100,
                'category_id' => $fashion->id,
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
                'category_id' => $fashion->id,
                'subcategory_id' => $menSubcat->id,
                'seller_id' => $sellerId,
                'gift_option' => 'yes',
                'delivery_charge' => 99,
                'discount' => 15,
                'image' => 'products/default-fashion.jpg',
            ],

            // Women's Clothing
            [
                'name' => 'Zara Floral Dress',
                'unique_id' => 'ZD1',
                'description' => 'Elegant floral print dress perfect for any occasion',
                'price' => 2999.00,
                'stock' => 60,
                'category_id' => $fashion->id,
                'subcategory_id' => $womenSubcat->id,
                'seller_id' => $sellerId,
                'gift_option' => 'yes',
                'delivery_charge' => 99,
                'discount' => 30,
                'image' => 'products/default-fashion.jpg',
            ],

            // Footwear
            [
                'name' => 'Nike Air Jordan 1',
                'unique_id' => 'NJ1',
                'description' => 'Iconic basketball shoes with premium leather construction',
                'price' => 12995.00,
                'stock' => 45,
                'category_id' => $fashion->id,
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
                'category_id' => $fashion->id,
                'subcategory_id' => $footwearSubcat->id,
                'seller_id' => $sellerId,
                'gift_option' => 'yes',
                'delivery_charge' => 199,
                'discount' => 22,
                'image' => 'products/default-fashion.jpg',
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['unique_id' => $product['unique_id']],
                $product
            );
        }
    }

    private function createHomeKitchenProducts($sellerId)
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
            Product::firstOrCreate(
                ['unique_id' => $product['unique_id']],
                $product
            );
        }
    }

    private function createBeautyProducts($sellerId)
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
            Product::firstOrCreate(
                ['unique_id' => $product['unique_id']],
                $product
            );
        }
    }

    private function createSportsProducts($sellerId)
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
            Product::firstOrCreate(
                ['unique_id' => $product['unique_id']],
                $product
            );
        }
    }

    private function createBooksProducts($sellerId)
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
                Product::firstOrCreate(
                    ['unique_id' => $product['unique_id']],
                    $product
                );
        }
    }

    private function createOtherProducts($sellerId)
    {
        // Toys
        $toys = Category::where('name', 'TOYS & GAMES')->first();
        $educationalSubcat = Subcategory::where('name', 'Educational Toys')->first();
        
        // Health
        $health = Category::where('name', 'HEALTH & WELLNESS')->first();
        $vitaminsSubcat = Subcategory::where('name', 'Vitamins & Supplements')->first();

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
                Product::firstOrCreate(
                    ['unique_id' => $product['unique_id']],
                    $product
                );
        }
    }
}
