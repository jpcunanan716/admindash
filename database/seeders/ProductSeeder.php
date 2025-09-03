<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::truncate();

        // Product::factory()->count(50)->create();

        $products = [   
            [
                'name' => 'Premium Coffee Beans',
                'category' => 'Food & Beverages',
                'description' => 'Freshly roasted premium coffee beans from Colombia. Perfect for espresso or drip coffee.',
                'dateTime' => Carbon::now()->addDays(30),
            ],
            [
                'name' => 'Organic Cotton T-Shirt',
                'category' => 'Clothing',
                'description' => '100% organic cotton t-shirt, comfortable and environmentally friendly. Available in multiple sizes.',
                'dateTime' => Carbon::now()->addDays(15),
            ],
            [
                'name' => 'Stainless Steel Water Bottle',
                'category' => 'Kitchenware',
                'description' => 'Double-walled insulated water bottle that keeps drinks cold for 24 hours or hot for 12 hours.',
                'dateTime' => Carbon::now()->addDays(45),
            ],
            [
                'name' => 'Wireless Bluetooth Headphones',
                'category' => 'Electronics',
                'description' => 'Noise-cancelling wireless headphones with 30-hour battery life and premium sound quality.',
                'dateTime' => Carbon::now()->addDays(60),
            ],
            [
                'name' => 'Yoga Mat',
                'category' => 'Fitness',
                'description' => 'Eco-friendly yoga mat with non-slip surface. Perfect for yoga, pilates, and exercise routines.',
                'dateTime' => Carbon::now()->addDays(20),
            ],
            [
                'name' => 'Handcrafted Ceramic Mug',
                'category' => 'Home Decor',
                'description' => 'Beautiful hand-painted ceramic mug with unique design. Microwave and dishwasher safe.',
                'dateTime' => Carbon::now()->addDays(10),
            ],
            [
                'name' => 'Natural Beeswax Candles',
                'category' => 'Home Decor',
                'description' => 'Set of 3 natural beeswax candles with honey scent. Each candle burns for 20+ hours.',
                'dateTime' => Carbon::now()->addDays(25),
            ],
            [
                'name' => 'Gourmet Chocolate Box',
                'category' => 'Food & Beverages',
                'description' => 'Assorted gourmet chocolates crafted by master chocolatiers. Perfect gift for any occasion.',
                'dateTime' => Carbon::now()->addDays(5),
            ],
            [
                'name' => 'Wooden Cutting Board',
                'category' => 'Kitchenware',
                'description' => 'Handcrafted bamboo cutting board with juice groove. Natural antibacterial properties.',
                'dateTime' => Carbon::now()->addDays(35),
            ],
            [
                'name' => 'Essential Oil Diffuser',
                'category' => 'Wellness',
                'description' => 'Ultrasonic essential oil diffuser with 7 color LED lights and auto-shutoff feature.',
                'dateTime' => Carbon::now()->addDays(50),
            ],
            [
                'name' => 'Leather Wallet',
                'category' => 'Accessories',
                'description' => 'Genuine leather wallet with multiple card slots and cash compartment. Handcrafted quality.',
                'dateTime' => Carbon::now()->addDays(40),
            ],
            [
                'name' => 'Cast Iron Skillet',
                'category' => 'Kitchenware',
                'description' => 'Pre-seasoned cast iron skillet for even heat distribution. Perfect for searing, baking, and frying.',
                'dateTime' => Carbon::now()->addDays(55),
            ],
            [
                'name' => 'Herbal Tea Sampler',
                'category' => 'Food & Beverages',
                'description' => 'Assortment of 12 organic herbal teas including chamomile, peppermint, and hibiscus.',
                'dateTime' => Carbon::now()->addDays(8),
            ],
            [
                'name' => 'Reusable Shopping Bags',
                'category' => 'Eco-Friendly',
                'description' => 'Set of 5 reusable shopping bags made from recycled materials. Foldable for easy storage.',
                'dateTime' => Carbon::now()->addDays(18),
            ],
            [
                'name' => 'Artisanal Olive Oil',
                'category' => 'Food & Beverages',
                'description' => 'Extra virgin olive oil from small family farms in Italy. Cold-pressed for maximum flavor.',
                'dateTime' => Carbon::now()->addDays(22),
            ],
            [
                'name' => 'Handmade Soap Collection',
                'category' => 'Personal Care',
                'description' => 'Set of 4 handmade soaps with natural ingredients like shea butter, oatmeal, and essential oils.',
                'dateTime' => Carbon::now()->addDays(12),
            ],
            [
                'name' => 'Portable Phone Charger',
                'category' => 'Electronics',
                'description' => '10000mAh portable power bank with fast charging capability. Compatible with all smartphones.',
                'dateTime' => Carbon::now()->addDays(65),
            ],
            [
                'name' => 'Scented Soy Candle',
                'category' => 'Home Decor',
                'description' => 'Natural soy candle with lavender scent. Burns cleanly for 40+ hours in a reusable glass container.',
                'dateTime' => Carbon::now()->addDays(28),
            ],
            [
                'name' => 'Stainless Steel Straw Set',
                'category' => 'Eco-Friendly',
                'description' => 'Set of 4 stainless steel straws with cleaning brush and carrying case. Reduce plastic waste.',
                'dateTime' => Carbon::now()->addDays(16),
            ],
            [
                'name' => 'Handwoven Throw Blanket',
                'category' => 'Home Decor',
                'description' => 'Soft handwoven throw blanket made from organic cotton. Perfect for cozying up on the couch.',
                'dateTime' => Carbon::now()->addDays(32),
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}