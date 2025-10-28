<?php

declare(strict_types=1);

/*
 * UserFrosting Commerce Sprinkle (http://www.userfrosting.com)
 *
 * @link      https://github.com/ssnukala/sprinkle-commerce
 * @copyright Copyright (c) 2025 Srinivas Nukala
 * @license   https://github.com/ssnukala/sprinkle-commerce/blob/main/LICENSE (MIT License)
 */

namespace UserFrosting\Sprinkle\Commerce\Tests\Schema;

use UserFrosting\Sprinkle\Commerce\Tests\CommerceTestCase;

/**
 * Tests for JSON schema validation
 */
class SchemaValidationTest extends CommerceTestCase
{
    /**
     * Test that sales_order schema file exists and is valid JSON
     */
    public function testSalesOrderSchemaExists(): void
    {
        $schemaPath = __DIR__ . '/../../../schema/crud6/sales_order.json';
        
        $this->assertFileExists($schemaPath, 'sales_order.json schema file should exist');
        
        $schemaContent = file_get_contents($schemaPath);
        $this->assertNotFalse($schemaContent, 'Schema file should be readable');
        
        $schema = json_decode($schemaContent, true);
        $this->assertNotNull($schema, 'Schema should be valid JSON');
        $this->assertIsArray($schema, 'Schema should decode to an array');
    }

    /**
     * Test sales_order schema has required fields
     */
    public function testSalesOrderSchemaStructure(): void
    {
        $schemaPath = __DIR__ . '/../../../schema/crud6/sales_order.json';
        $schema = json_decode(file_get_contents($schemaPath), true);

        // Check required top-level keys
        $this->assertArrayHasKey('model', $schema);
        $this->assertArrayHasKey('table', $schema);
        $this->assertArrayHasKey('fields', $schema);
        
        // Verify model name
        $this->assertEquals('sales_order', $schema['model']);
        $this->assertEquals('or_sales_order', $schema['table']);
        
        // Check some key fields exist
        $this->assertArrayHasKey('id', $schema['fields']);
        $this->assertArrayHasKey('user_id', $schema['fields']);
        $this->assertArrayHasKey('gross_amount', $schema['fields']);
        $this->assertArrayHasKey('status', $schema['fields']);
    }

    /**
     * Test sales_order schema field types
     */
    public function testSalesOrderSchemaFieldTypes(): void
    {
        $schemaPath = __DIR__ . '/../../../schema/crud6/sales_order.json';
        $schema = json_decode(file_get_contents($schemaPath), true);

        $fields = $schema['fields'];

        // Check field types
        $this->assertEquals('integer', $fields['id']['type']);
        $this->assertEquals('integer', $fields['user_id']['type']);
        $this->assertEquals('string', $fields['name']['type']);
        $this->assertEquals('decimal', $fields['gross_amount']['type']);
        $this->assertEquals('datetime', $fields['order_date']['type']);
        $this->assertEquals('json', $fields['meta']['type']);
    }

    /**
     * Test purchase_order schema file exists and is valid
     */
    public function testPurchaseOrderSchemaExists(): void
    {
        $schemaPath = __DIR__ . '/../../../schema/crud6/purchase_order.json';
        
        $this->assertFileExists($schemaPath, 'purchase_order.json schema file should exist');
        
        $schemaContent = file_get_contents($schemaPath);
        $this->assertNotFalse($schemaContent, 'Schema file should be readable');
        
        $schema = json_decode($schemaContent, true);
        $this->assertNotNull($schema, 'Schema should be valid JSON');
        $this->assertIsArray($schema, 'Schema should decode to an array');
    }

    /**
     * Test purchase_order schema structure
     */
    public function testPurchaseOrderSchemaStructure(): void
    {
        $schemaPath = __DIR__ . '/../../../schema/crud6/purchase_order.json';
        $schema = json_decode(file_get_contents($schemaPath), true);

        $this->assertArrayHasKey('model', $schema);
        $this->assertArrayHasKey('table', $schema);
        $this->assertArrayHasKey('fields', $schema);
        
        $this->assertEquals('purchase_order', $schema['model']);
        $this->assertEquals('or_purchase_order', $schema['table']);
    }

    /**
     * Test sales_order_lines schema
     */
    public function testSalesOrderLinesSchemaExists(): void
    {
        $schemaPath = __DIR__ . '/../../../schema/crud6/sales_order_lines.json';
        
        $this->assertFileExists($schemaPath);
        
        $schema = json_decode(file_get_contents($schemaPath), true);
        $this->assertNotNull($schema);
        $this->assertEquals('sales_order_lines', $schema['model']);
        $this->assertEquals('or_sales_order_lines', $schema['table']);
    }

    /**
     * Test purchase_order_lines schema
     */
    public function testPurchaseOrderLinesSchemaExists(): void
    {
        $schemaPath = __DIR__ . '/../../../schema/crud6/purchase_order_lines.json';
        
        $this->assertFileExists($schemaPath);
        
        $schema = json_decode(file_get_contents($schemaPath), true);
        $this->assertNotNull($schema);
        $this->assertEquals('purchase_order_lines', $schema['model']);
        $this->assertEquals('or_purchase_order_lines', $schema['table']);
    }

    /**
     * Test that detail sections are configured correctly
     */
    public function testDetailSectionConfiguration(): void
    {
        $schemaPath = __DIR__ . '/../../../schema/crud6/sales_order.json';
        $schema = json_decode(file_get_contents($schemaPath), true);

        $this->assertArrayHasKey('detail', $schema);
        $this->assertArrayHasKey('model', $schema['detail']);
        $this->assertArrayHasKey('foreign_key', $schema['detail']);
        
        $this->assertEquals('sales_order_lines', $schema['detail']['model']);
        $this->assertEquals('order_id', $schema['detail']['foreign_key']);
    }

    /**
     * Test that permissions are defined
     */
    public function testSchemaPermissions(): void
    {
        $schemaPath = __DIR__ . '/../../../schema/crud6/sales_order.json';
        $schema = json_decode(file_get_contents($schemaPath), true);

        $this->assertArrayHasKey('permissions', $schema);
        $this->assertArrayHasKey('read', $schema['permissions']);
        $this->assertArrayHasKey('create', $schema['permissions']);
        $this->assertArrayHasKey('update', $schema['permissions']);
        $this->assertArrayHasKey('delete', $schema['permissions']);
    }
}
