<?php

declare(strict_types=1);

/*
 * UserFrosting Commerce Sprinkle (http://www.userfrosting.com)
 *
 * @link      https://github.com/ssnukala/sprinkle-commerce
 * @copyright Copyright (c) 2025 Srinivas Nukala
 * @license   https://github.com/ssnukala/sprinkle-commerce/blob/main/LICENSE (MIT License)
 */

namespace UserFrosting\Sprinkle\Commerce\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder for product categories
 */
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics', 'description' => 'Electronic devices and gadgets', 'slug' => 'electronics', 'type' => 'PR', 'status' => 'A'],
            ['name' => 'Computers', 'description' => 'Desktop and laptop computers', 'slug' => 'computers', 'type' => 'PR', 'status' => 'A'],
            ['name' => 'Smartphones', 'description' => 'Mobile phones and accessories', 'slug' => 'smartphones', 'type' => 'PR', 'status' => 'A'],
            ['name' => 'Tablets', 'description' => 'Tablet computers and accessories', 'slug' => 'tablets', 'type' => 'PR', 'status' => 'A'],
            ['name' => 'Audio', 'description' => 'Headphones, speakers, and audio equipment', 'slug' => 'audio', 'type' => 'PR', 'status' => 'A'],
            ['name' => 'Cameras', 'description' => 'Digital cameras and photography equipment', 'slug' => 'cameras', 'type' => 'PR', 'status' => 'A'],
            ['name' => 'Wearables', 'description' => 'Smartwatches and fitness trackers', 'slug' => 'wearables', 'type' => 'PR', 'status' => 'A'],
            ['name' => 'Gaming', 'description' => 'Gaming consoles and accessories', 'slug' => 'gaming', 'type' => 'PR', 'status' => 'A'],
            ['name' => 'Home Appliances', 'description' => 'Kitchen and home appliances', 'slug' => 'home-appliances', 'type' => 'PR', 'status' => 'A'],
            ['name' => 'Office Supplies', 'description' => 'Office furniture and supplies', 'slug' => 'office-supplies', 'type' => 'PR', 'status' => 'A'],
            ['name' => 'Books', 'description' => 'Physical and digital books', 'slug' => 'books', 'type' => 'PR', 'status' => 'A'],
            ['name' => 'Clothing', 'description' => 'Apparel and fashion items', 'slug' => 'clothing', 'type' => 'PR', 'status' => 'A'],
            ['name' => 'Sports & Outdoors', 'description' => 'Sports equipment and outdoor gear', 'slug' => 'sports-outdoors', 'type' => 'PR', 'status' => 'A'],
            ['name' => 'Toys & Games', 'description' => 'Toys and board games', 'slug' => 'toys-games', 'type' => 'PR', 'status' => 'A'],
            ['name' => 'Health & Beauty', 'description' => 'Health and beauty products', 'slug' => 'health-beauty', 'type' => 'PR', 'status' => 'A'],
            ['name' => 'Automotive', 'description' => 'Car parts and accessories', 'slug' => 'automotive', 'type' => 'PR', 'status' => 'A'],
            ['name' => 'Garden & Tools', 'description' => 'Garden equipment and tools', 'slug' => 'garden-tools', 'type' => 'PR', 'status' => 'A'],
            ['name' => 'Pet Supplies', 'description' => 'Pet food and accessories', 'slug' => 'pet-supplies', 'type' => 'PR', 'status' => 'A'],
            ['name' => 'Food & Beverages', 'description' => 'Groceries and beverages', 'slug' => 'food-beverages', 'type' => 'PR', 'status' => 'A'],
            ['name' => 'Jewelry', 'description' => 'Jewelry and watches', 'slug' => 'jewelry', 'type' => 'PR', 'status' => 'A'],
        ];

        foreach ($categories as $category) {
            DB::table('pr_category')->insert(array_merge($category, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
