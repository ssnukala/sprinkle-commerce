<?php

declare(strict_types=1);

/*
 * UserFrosting Commerce Sprinkle (http://www.userfrosting.com)
 *
 * @link      https://github.com/ssnukala/sprinkle-commerce
 * @copyright Copyright (c) 2025 Srinivas Nukala
 * @license   https://github.com/ssnukala/sprinkle-commerce/blob/main/LICENSE (MIT License)
 */

namespace UserFrosting\Sprinkle\Commerce;

use UserFrosting\Sprinkle\Account\Account;
use UserFrosting\Sprinkle\Admin\Admin;
use UserFrosting\Sprinkle\Core\Core;
use UserFrosting\Sprinkle\CRUD6\CRUD6;
use UserFrosting\Sprinkle\SprinkleRecipe;
use UserFrosting\Sprinkle\Commerce\Database\Migrations\v600\CatalogTable;
use UserFrosting\Sprinkle\Commerce\Database\Migrations\v600\CategoryTable;
use UserFrosting\Sprinkle\Commerce\Database\Migrations\v600\ProductTable;
use UserFrosting\Sprinkle\Commerce\Database\Migrations\v600\ProductCatalogTable;
use UserFrosting\Sprinkle\Commerce\Database\Migrations\v600\ProductRolesTable;

/**
 * Commerce Sprinkle - eCommerce Solution for UserFrosting 6
 *
 * Combines order management and product catalog functionality:
 * 
 * ORDER MANAGEMENT:
 * - Sales Orders with line items
 * - Purchase Orders with line items
 * - Shopping Cart functionality
 * - Order processing and checkout workflows
 * 
 * PRODUCT CATALOG:
 * - Products with categories
 * - Catalogs for organizing products
 * - Product-Catalog relationships
 * - Category management
 *
 * PAYMENT PROCESSING:
 * - Requires sprinkle-payment as a peer dependency for payment processing
 *
 * This sprinkle uses CRUD6 for all CRUD operations on commerce-related tables.
 * All routes are automatically provided by CRUD6 via JSON schemas in app/schema/crud6/.
 * No custom models needed - CRUD6's generic model system handles everything.
 * Detail sections in schemas define one-to-many relationships.
 */
class Commerce implements SprinkleRecipe
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'Commerce Sprinkle';
    }

    /**
     * {@inheritdoc}
     */
    public function getPath(): string
    {
        return __DIR__ . '/../';
    }

    /**
     * {@inheritdoc}
     */
    public function getSprinkles(): array
    {
        return [
            Core::class,
            Account::class,
            Admin::class,
            CRUD6::class,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getRoutes(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function getServices(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function getMigrations(): array
    {
        return [
            // Product catalog migrations (v600)
            CatalogTable::class,
            CategoryTable::class,
            ProductTable::class,
            ProductCatalogTable::class,
            ProductRolesTable::class,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getListeners(): array
    {
        return [];
    }
}
