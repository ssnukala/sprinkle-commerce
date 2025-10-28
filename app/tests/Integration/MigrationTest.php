<?php

declare(strict_types=1);

/*
 * UserFrosting Commerce Sprinkle (http://www.userfrosting.com)
 *
 * @link      https://github.com/ssnukala/sprinkle-commerce
 * @copyright Copyright (c) 2025 Srinivas Nukala
 * @license   https://github.com/ssnukala/sprinkle-commerce/blob/main/LICENSE (MIT License)
 */

namespace UserFrosting\Sprinkle\Commerce\Tests\Integration;

use UserFrosting\Sprinkle\Commerce\Tests\CommerceTestCase;
use UserFrosting\Sprinkle\Commerce\Database\Migrations\v600\SalesOrderTable;
use UserFrosting\Sprinkle\Commerce\Database\Migrations\v600\SalesOrderLinesTable;
use UserFrosting\Sprinkle\Commerce\Database\Migrations\v600\PurchaseOrderTable;
use UserFrosting\Sprinkle\Commerce\Database\Migrations\v600\PurchaseOrderLinesTable;
use UserFrosting\Sprinkle\Commerce\Database\Migrations\v600\CommerceRolesTable;

/**
 * Integration tests for migration registration
 */
class MigrationTest extends CommerceTestCase
{
    /**
     * Test that all required migrations are registered
     */
    public function testMigrationsAreRegistered(): void
    {
        // Get the migrations from the sprinkle
        $migrations = $this->ci->get('sprinkleManager')
            ->getSprinkle(\UserFrosting\Sprinkle\Commerce\Commerce::class)
            ->getMigrations();

        // Check that order migrations are registered
        $this->assertContains(SalesOrderTable::class, $migrations);
        $this->assertContains(SalesOrderLinesTable::class, $migrations);
        $this->assertContains(PurchaseOrderTable::class, $migrations);
        $this->assertContains(PurchaseOrderLinesTable::class, $migrations);
        
        // Check that commerce roles migration is registered
        $this->assertContains(CommerceRolesTable::class, $migrations);
    }

    /**
     * Test that migration classes exist and are valid
     */
    public function testMigrationClassesExist(): void
    {
        $this->assertTrue(class_exists(SalesOrderTable::class));
        $this->assertTrue(class_exists(SalesOrderLinesTable::class));
        $this->assertTrue(class_exists(PurchaseOrderTable::class));
        $this->assertTrue(class_exists(PurchaseOrderLinesTable::class));
        $this->assertTrue(class_exists(CommerceRolesTable::class));
    }
}
