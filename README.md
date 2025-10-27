# UserFrosting 6 Commerce Sprinkle

An eCommerce solution for UserFrosting 6, combining order management and product catalog functionality.

Built on top of **[sprinkle-crud6](https://github.com/ssnukala/sprinkle-crud6)** for robust CRUD operations and API layer.

## Overview

This sprinkle provides:
- **sprinkle-orders**: Order and cart management
- **sprinkle-products**: Product catalog and category management

For payment processing, use **[sprinkle-payment](https://github.com/ssnukala/sprinkle-payment)** as a companion sprinkle.

## Features

### 🛒 Order Management
- **Sales Orders** with line items
- **Purchase Orders** with line items
- Shopping Cart functionality with Vue.js components
- Order workflow and status management
- Mini cart widget for e-commerce sites

### 📦 Product Catalog
- **Product Management**: Full product CRUD with rich attributes
- **Categories**: Hierarchical category organization
- **Catalogs**: Organize products into multiple catalogs
- **Product-Catalog Relationships**: Flexible product organization

### 💳 Payment Processing (via sprinkle-payment)
For payment processing capabilities, install **[sprinkle-payment](https://github.com/ssnukala/sprinkle-payment)** which provides:
- Multiple payment gateways (Stripe, PayPal, Apple Pay, Google Pay, Manual Check)
- Payment tracking and refunds
- Webhook handlers

## Architecture

This sprinkle follows a **schema-driven architecture** using CRUD6:

- **No Custom Model Classes**: All models are defined via JSON schemas in `app/schema/crud6/`
- **Generic CRUD Operations**: Full CRUD via CRUD6's SchemaService
- **RESTful APIs**: Auto-generated CRUD6 endpoints
- **Vue.js Components**: Modern frontend components for cart

## Requirements

- PHP 8.1 or later
- UserFrosting 6.0 beta or later
- sprinkle-crud6 ^1.0
- Vue 3.3+ (for frontend components)

## Installation

1. Install via Composer:

```bash
composer require ssnukala/sprinkle-commerce
```

2. (Optional) For payment processing, also install:

```bash
composer require ssnukala/sprinkle-payment
```

3. Add the sprinkle to your application's main sprinkle class:

```php
use UserFrosting\Sprinkle\Commerce\Commerce;

public function getSprinkles(): array
{
    return [
        Core::class,
        Account::class,
        Admin::class,
        CRUD6::class,
        Commerce::class,  // Add this
        // Payment::class,  // Add this if using payment processing
        // ... your other sprinkles
    ];
}
```

4. Run migrations:

```bash
php bakery migrate
```

## Database Schema

This sprinkle provides the following tables via migrations:

### Order Tables (Schema only - no migrations)
- **sales_order**: Sales order information
- **sales_order_lines**: Sales order line items
- **purchase_order**: Purchase order information
- **purchase_order_lines**: Purchase order line items

### Product Catalog Tables (with migrations)
- **product**: Product information
- **category**: Product categories
- **catalog**: Product catalogs
- **product_catalog**: Product-catalog relationships
- **product_roles**: Product role permissions

All models are defined via CRUD6 JSON schemas in `app/schema/crud6/`.

## API Endpoints

All CRUD operations are handled through the CRUD6 API:

### Sales Orders
- List: `GET /api/crud6/sales_order`
- Create: `POST /api/crud6/sales_order`
- Read: `GET /api/crud6/sales_order/{id}`
- Update: `PUT /api/crud6/sales_order/{id}`
- Delete: `DELETE /api/crud6/sales_order/{id}`
- Line Items: `GET /api/crud6/sales_order/{id}/lines`

### Purchase Orders
- List: `GET /api/crud6/purchase_order`
- Create: `POST /api/crud6/purchase_order`
- Read: `GET /api/crud6/purchase_order/{id}`
- Update: `PUT /api/crud6/purchase_order/{id}`
- Delete: `DELETE /api/crud6/purchase_order/{id}`
- Line Items: `GET /api/crud6/purchase_order/{id}/lines`
- Details: `GET /api/crud6/payment/{id}/details`

### Products
- List: `GET /api/crud6/product`
- Create: `POST /api/crud6/product`
- Read: `GET /api/crud6/product/{id}`
- Update: `PUT /api/crud6/product/{id}`
- Delete: `DELETE /api/crud6/product/{id}`

### Categories
- List: `GET /api/crud6/category`
- Create: `POST /api/crud6/category`
- Read: `GET /api/crud6/category/{id}`
- Update: `PUT /api/crud6/category/{id}`
- Delete: `DELETE /api/crud6/category/{id}`
- Products: `GET /api/crud6/category/{id}/products`

### Catalogs
- List: `GET /api/crud6/catalog`
- Create: `POST /api/crud6/catalog`
- Read: `GET /api/crud6/catalog/{id}`
- Update: `PUT /api/crud6/catalog/{id}`
- Delete: `DELETE /api/crud6/catalog/{id}`

## Usage Examples

### Working with Models Programmatically

```php
use UserFrosting\Sprinkle\CRUD6\ServicesProvider\SchemaService;

// In a controller or service
public function __construct(protected SchemaService $schemaService) {}

public function createOrder()
{
    // Get a model instance configured from schema
    $salesOrderModel = $this->schemaService->getModelInstance('sales_order');
    
    // Create a new order
    $order = $salesOrderModel->create([
        'name' => 'Order #123',
        'user_id' => $userId,
        'status' => 'P', // Pending
        'total' => 99.99,
    ]);
    
    // Add line items
    $linesModel = $this->schemaService->getModelInstance('sales_order_lines');
    $linesModel->create([
        'order_id' => $order->id,
        'product_id' => $productId,
        'quantity' => 2,
        'unit_price' => 49.99,
    ]);
}
```

## Frontend Components

### Mini Cart Component

```vue
<template>
  <MiniCart />
</template>

<script setup lang="ts">
import { MiniCart } from '@ssnukala/sprinkle-commerce';
</script>
```

### Using Cart Composable

```typescript
import { useCart } from '@ssnukala/sprinkle-commerce';

const { cart, addItem, removeItem, updateQuantity, clearCart } = useCart();

// Add item to cart
addItem({
  product_id: 123,
  quantity: 2,
  unit_price: 29.99,
  name: 'Product Name'
});
```

## Testing

Run tests using:

```bash
composer test
```

Run PHP code style fixer:

```bash
composer phpcs
```

Run static analysis:

```bash
composer phpstan
```

## CRUD6 Integration

This sprinkle is built on top of [sprinkle-crud6](https://github.com/ssnukala/sprinkle-crud6), which provides:

- **Generic CRUD API Layer**: Standardized REST API patterns
- **JSON Schema Support**: Data validation and structure
- **Schema-Driven Models**: No custom Eloquent model classes needed
- **Automatic Relationships**: Via detail sections in schemas
- **Filtering and Pagination**: Built-in query support
- **Type Safety**: Automatic type casting from schemas

## License

MIT License. See [LICENSE](LICENSE) file for details.

## Author

Srinivas Nukala - [https://srinivasnukala.com](https://srinivasnukala.com)

## Support

- Issues: [GitHub Issues](https://github.com/ssnukala/sprinkle-commerce/issues)
- Documentation: [GitHub Wiki](https://github.com/ssnukala/sprinkle-commerce/wiki)
- UserFrosting: [https://www.userfrosting.com](https://www.userfrosting.com)
