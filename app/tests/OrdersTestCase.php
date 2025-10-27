<?php

declare(strict_types=1);

/*
 * UserFrosting Orders Sprinkle (http://www.userfrosting.com)
 *
 * @link      https://github.com/ssnukala/sprinkle-orders
 * @copyright Copyright (c) 2024 Srinivas Nukala
 * @license   https://github.com/ssnukala/sprinkle-orders/blob/master/LICENSE (MIT License)
 */

namespace UserFrosting\Sprinkle\Orders\Tests;

use UserFrosting\Sprinkle\Orders\Orders;
use UserFrosting\Testing\TestCase;

/**
 * Test case with Orders as main sprinkle
 */
class OrdersTestCase extends TestCase
{
    protected string $mainSprinkle = Orders::class;
}
