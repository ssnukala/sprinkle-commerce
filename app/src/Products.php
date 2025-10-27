<?php

declare(strict_types=1);

/*
 * UserFrosting Products Sprinkle (http://www.userfrosting.com)
 *
 * @link      https://github.com/ssnukala/sprinkle-products
 * @copyright Copyright (c) 2024 Srinivas Nukala
 * @license   https://github.com/ssnukala/sprinkle-products/blob/master/LICENSE (MIT License)
 */

namespace UserFrosting\Sprinkle\Commerce;

use UserFrosting\Sprinkle\Account\Account;
use UserFrosting\Sprinkle\Admin\Admin;
use UserFrosting\Sprinkle\Core\Core;
use UserFrosting\Sprinkle\CRUD6\CRUD6;
use UserFrosting\Sprinkle\SprinkleRecipe;

/**
 * Products Sprinkle - Product Management System for UserFrosting 6
 *
 * Provides comprehensive product management functionality including:
 * - Products with categories
 * - Catalogs for organizing products
 * - Product-Catalog relationships
 * - Category management
 *
 * This sprinkle uses CRUD6 for all CRUD operations on product-related tables.
 * All routes are automatically provided by CRUD6 via JSON schemas in app/schema/crud6/.
 * No custom models needed - CRUD6's generic model system handles everything.
 * Detail sections in schemas define one-to-many relationships (category → products, catalog → product_catalog).
 */
class Products implements SprinkleRecipe
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'Products Sprinkle';
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
     * 
     * All routes are provided by CRUD6 sprinkle.
     * Custom routes can be added here when needed for business logic
     * not covered by standard CRUD operations.
     */
    public function getRoutes(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     * 
     * No custom services needed yet.
     * Service providers can be added here when custom business logic
     * or services are required.
     */
    public function getServices(): array
    {
        return [];
    }
}
