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

/**
 * Master seeder for Commerce sprinkle
 * 
 * Seeds all commerce tables with sample data for testing
 */
class CommerceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed in proper order to respect foreign key constraints
        $this->call([
            CategorySeeder::class,
            CatalogSeeder::class,
            ProductSeeder::class,
            ProductCatalogSeeder::class,
            SalesOrderSeeder::class,
            SalesOrderLinesSeeder::class,
            PurchaseOrderSeeder::class,
            PurchaseOrderLinesSeeder::class,
        ]);
    }
}
