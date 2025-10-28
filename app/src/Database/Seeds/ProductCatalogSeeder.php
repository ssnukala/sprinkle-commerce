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

use Illuminate\Database\Connection;
use UserFrosting\Sprinkle\Core\Seeder\SeedInterface;

/**
 * Seeder for product-catalog relationships
 */
class ProductCatalogSeeder implements SeedInterface
{
    /**
     * Constructor
     */
    public function __construct(
        protected Connection $db,
    ) {
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');
        $relationships = [];

        // Add all products to Main Catalog (catalog_id: 1)
        for ($productId = 1; $productId <= 30; $productId++) {
            $relationships[] = [
                'product_id' => $productId,
                'catalog_id' => 1,
                'name' => 'Main Catalog Listing',
                'description' => 'Standard listing in main catalog',
                'unit_price' => null, // Use product's default price
                'tax' => 0.00,
                'status' => 'A',
                'active_date' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Holiday Sale (catalog_id: 2) - 10 selected products with discounts
        $holidayProducts = [1, 4, 7, 10, 12, 15, 18, 20, 23, 27];
        foreach ($holidayProducts as $productId) {
            $relationships[] = [
                'product_id' => $productId,
                'catalog_id' => 2,
                'name' => 'Holiday Special',
                'description' => 'Special holiday pricing',
                'unit_price' => null,
                'tax' => 0.00,
                'status' => 'A',
                'active_date' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Back to School (catalog_id: 3) - Computers, tablets, office supplies
        $schoolProducts = [2, 4, 5, 6, 10, 11, 23, 24, 25, 26];
        foreach ($schoolProducts as $productId) {
            $relationships[] = [
                'product_id' => $productId,
                'catalog_id' => 3,
                'name' => 'Back to School Item',
                'description' => 'Essential for students',
                'unit_price' => null,
                'tax' => 0.00,
                'status' => 'A',
                'active_date' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // New Arrivals (catalog_id: 7) - Latest products
        $newProducts = [1, 2, 3, 7, 9, 12, 14, 18];
        foreach ($newProducts as $productId) {
            $relationships[] = [
                'product_id' => $productId,
                'catalog_id' => 7,
                'name' => 'New Arrival',
                'description' => 'Recently added product',
                'unit_price' => null,
                'tax' => 0.00,
                'status' => 'A',
                'active_date' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Best Sellers (catalog_id: 8) - Popular items
        $bestSellers = [4, 7, 12, 18, 19, 20, 23, 27];
        foreach ($bestSellers as $productId) {
            $relationships[] = [
                'product_id' => $productId,
                'catalog_id' => 8,
                'name' => 'Best Seller',
                'description' => 'Top selling product',
                'unit_price' => null,
                'tax' => 0.00,
                'status' => 'A',
                'active_date' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Premium Products (catalog_id: 9) - High-end items
        $premiumProducts = [4, 6, 9, 15, 18, 19];
        foreach ($premiumProducts as $productId) {
            $relationships[] = [
                'product_id' => $productId,
                'catalog_id' => 9,
                'name' => 'Premium Product',
                'description' => 'High-end quality item',
                'unit_price' => null,
                'tax' => 0.00,
                'status' => 'A',
                'active_date' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach ($relationships as $relation) {
            $this->db->table('pr_product_catalog')->insert($relation);
        }
    }
}
