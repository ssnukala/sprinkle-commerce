<?php

declare(strict_types=1);

/*
 * UserFrosting Commerce Sprinkle (http://www.userfrosting.com)
 *
 * @link      https://github.com/ssnukala/sprinkle-commerce
 * @copyright Copyright (c) 2025 Srinivas Nukala
 * @license   https://github.com/ssnukala/sprinkle-commerce/blob/main/LICENSE (MIT License)
 */

namespace UserFrosting\Sprinkle\Commerce\Tests;

use UserFrosting\Sprinkle\Commerce\Commerce;
use UserFrosting\Testing\TestCase;

/**
 * Test case with Commerce as main sprinkle
 */
class CommerceTestCase extends TestCase
{
    protected string $mainSprinkle = Commerce::class;
}
