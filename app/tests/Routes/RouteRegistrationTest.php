<?php

declare(strict_types=1);

/*
 * UserFrosting Commerce Sprinkle (http://www.userfrosting.com)
 *
 * @link      https://github.com/ssnukala/sprinkle-commerce
 * @copyright Copyright (c) 2025 Srinivas Nukala
 * @license   https://github.com/ssnukala/sprinkle-commerce/blob/main/LICENSE (MIT License)
 */

namespace UserFrosting\Sprinkle\Commerce\Tests\Routes;

use UserFrosting\Sprinkle\Commerce\Tests\CommerceTestCase;

/**
 * Tests for route registration
 */
class RouteRegistrationTest extends CommerceTestCase
{
    /**
     * Test that Commerce sprinkle is loaded
     */
    public function testCommerceSprinkleIsLoaded(): void
    {
        $sprinkles = $this->ci->get('sprinkleManager')->getSprinkles();
        
        $sprinkleNames = array_map(function ($sprinkle) {
            return get_class($sprinkle);
        }, $sprinkles);
        
        $this->assertContains(
            'UserFrosting\Sprinkle\Commerce\Commerce',
            $sprinkleNames,
            'Commerce sprinkle should be loaded'
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
