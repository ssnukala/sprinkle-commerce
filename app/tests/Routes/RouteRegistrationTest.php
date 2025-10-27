<?php

declare(strict_types=1);

/*
 * UserFrosting Orders Sprinkle (http://www.userfrosting.com)
 *
 * @link      https://github.com/ssnukala/sprinkle-orders
 * @copyright Copyright (c) 2024 Srinivas Nukala
 * @license   https://github.com/ssnukala/sprinkle-orders/blob/master/LICENSE (MIT License)
 */

namespace UserFrosting\Sprinkle\Orders\Tests\Routes;

use UserFrosting\Sprinkle\Orders\Tests\OrdersTestCase;

/**
 * Tests for route registration
 */
class RouteRegistrationTest extends OrdersTestCase
{
    /**
     * Test that Orders sprinkle is loaded
     */
    public function testOrdersSprinkleIsLoaded(): void
    {
        $sprinkles = $this->ci->get('sprinkleManager')->getSprinkles();
        
        $sprinkleNames = array_map(function ($sprinkle) {
            return get_class($sprinkle);
        }, $sprinkles);
        
        $this->assertContains(
            'UserFrosting\Sprinkle\Orders\Orders',
            $sprinkleNames,
            'Orders sprinkle should be loaded'
        );
    }

    /**
     * Test that CRUD6 sprinkle is loaded (dependency)
     */
    public function testCRUD6SprinkleIsLoaded(): void
    {
        $sprinkles = $this->ci->get('sprinkleManager')->getSprinkles();
        
        $sprinkleNames = array_map(function ($sprinkle) {
            return get_class($sprinkle);
        }, $sprinkles);
        
        $this->assertContains(
            'UserFrosting\Sprinkle\CRUD6\CRUD6',
            $sprinkleNames,
            'CRUD6 sprinkle should be loaded as a dependency'
        );
    }

    /**
     * Test that routes are accessible
     */
    public function testRoutesAreRegistered(): void
    {
        $routeCollector = $this->ci->get('routeCollector');
        $routes = $routeCollector->getRoutes();
        
        $this->assertNotEmpty($routes, 'Routes should be registered');
    }
}
