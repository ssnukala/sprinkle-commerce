# sprinkle-commerce Development Guidelines

This sprinkle combines Order Management, Payment Processing, and Product Catalog functionality into a comprehensive eCommerce solution for UserFrosting 6. It depends on **[sprinkle-crud6](https://github.com/ssnukala/sprinkle-crud6)** (main branch) for all CRUD operations.

Always reference these instructions first and fallback to search or bash commands only when you encounter unexpected information that does not match the info here.

## 🎯 CRITICAL ARCHITECTURE - CRUD6 Integration

### Core Principle: No Redundant Code

**This sprinkle uses sprinkle-crud6 for ALL CRUD operations. DO NOT create redundant code that duplicates CRUD6 functionality.**

### What sprinkle-crud6 Provides

sprinkle-crud6 (main branch) provides a complete generic CRUD layer:

1. **Generic Model System**
   - `CRUD6Model` - Dynamically configured from JSON schemas
   - Full Eloquent ORM support
   - Automatic type casting, fillable fields, timestamps, soft deletes
   - No custom model classes needed

2. **RESTful API Endpoints**
   - Automatic routes: `/api/crud6/{model}`
   - Full CRUD operations (Create, Read, Update, Delete)
   - Filtering, sorting, pagination
   - Relationship management via detail sections

3. **Schema-Driven Configuration**
   - Everything defined in JSON schemas (`app/schema/crud6/*.json`)
   - Detail sections for one-to-many relationships
   - Field validation, permissions, default sorting
   - No duplication in PHP model classes

4. **Relationship Management**
   - Detail sections in schemas define one-to-many relationships
   - API endpoint: `GET /api/crud6/{model}/{id}/{relation}`
   - Foreign key configuration in JSON
   - No need for custom Eloquent relationship methods

### What This Sprinkle Contains

**✅ DO Include:**
- JSON schema files in `app/schema/crud6/` for all commerce entities
- Custom business logic controllers for workflows beyond CRUD (cart, checkout, payment)
- Custom services for complex business logic (OrderService, CartService, PaymentService)
- Custom routes for non-CRUD operations
- Tests for custom business logic
- Payment processor implementations (Stripe, PayPal, Apple Pay, Google Pay, Manual Check)
- Webhook handlers for payment gateway notifications

**❌ DO NOT Include:**
- Custom Eloquent model classes (use CRUD6's generic model system)
- Duplicate table configuration (already in schemas)
- Duplicate fillable/casts arrays (already in schemas)
- Custom relationship methods for simple one-to-many (use detail sections)
- Controllers that duplicate CRUD6's CRUD operations
- Runtime folders (`app/cache`, `app/logs`, `app/sessions`, `app/storage`) - these are handled by the main UserFrosting application, not individual sprinkles

## 📁 Directory Structure Policy

**DO NOT create empty directories:**
- Only create directories when they will immediately contain files or subdirectories with files
- Empty folders serve no purpose and clutter the repository
- Parent directories are acceptable only if they organize actual content files

**UserFrosting 6 Standard Structure:**
```
app/
├── assets/         # Frontend assets (CSS, JS, Vue components)
├── config/         # Configuration files
├── locale/         # Translations
├── schema/         # CRUD6 JSON schemas
│   └── crud6/      # Schema files for all commerce entities
├── src/            # PHP source code
│   ├── Controller/ # Business logic controllers
│   ├── Database/   # Migrations and repositories
│   │   ├── Migrations/
│   │   │   ├── v100/  # Payment migrations
│   │   │   └── v401/  # Product catalog migrations
│   │   └── Repositories/
│   ├── Routes/     # Custom routes
│   └── Services/   # Business services and payment processors
├── templates/      # Twig templates
└── tests/          # Unit/integration tests
```

**Guidelines:**
- Before creating a directory, ensure you have the file(s) to place in it
- Remove any directories that become empty after deleting files
- Don't create placeholder or "future use" directories
- Parent directories (e.g., `app/src/Database/`) are fine if subdirectories contain files

## 🛍️ COMMERCE FEATURES

### Order Management
- **Sales Orders** with line items (schema: `sales_order`, `sales_order_lines`)
- **Purchase Orders** with line items (schema: `purchase_order`, `purchase_order_lines`)
- Shopping Cart functionality (Vue components in `app/assets/components/Cart/`)
- Order workflow and status management
- Mini cart widget for e-commerce

### Payment Processing
- **Payment Models** (schema: `payment`, `payment_detail`)
- **Payment Gateways**: Stripe, PayPal, Apple Pay, Google Pay, Manual Check
- Official SDKs: Stripe.js, PayPal JavaScript SDK, Payment Request API
- Payment tracking with detailed status and transaction information
- Refund support through supported payment gateways
- Webhook handlers for payment gateway notifications
- PaymentService for orchestrating payment workflows

### Product Catalog
- **Products** (schema: `product`)
- **Categories** (schema: `category`)
- **Catalogs** (schema: `catalog`)
- **Product-Catalog Relationships** (schema: `product_catalog`)
- Category management with hierarchical support
- Product organization across multiple catalogs

## 🗄️ Database Schema

All models are defined via CRUD6 JSON schemas in `app/schema/crud6/`:

### Order Schemas
- `sales_order.json` - Sales order information
- `sales_order_lines.json` - Sales order line items
- `purchase_order.json` - Purchase order information
- `purchase_order_lines.json` - Purchase order line items

### Payment Schemas
- `payment.json` - Payment transactions (one order can have multiple payments)
- `payment_detail.json` - Detailed payment transaction information

### Product Schemas
- `product.json` - Product information
- `category.json` - Product categories
- `catalog.json` - Product catalogs
- `product_catalog.json` - Product-catalog relationships

## 🔧 Using CRUD6's Generic Model System

When you need to work with models programmatically:

```php
use UserFrosting\Sprinkle\CRUD6\ServicesProvider\SchemaService;

// In a controller or service
public function __construct(protected SchemaService $schemaService) {}

public function myMethod()
{
    // Get a model instance configured from schema
    $salesOrderModel = $this->schemaService->getModelInstance('sales_order');
    
    // Use like any Eloquent model
    $activeOrders = $salesOrderModel->where('status', 'A')->get();
    
    // Create records
    $newOrder = $salesOrderModel->create([
        'name' => 'My Order',
        'user_id' => $userId,
        // ... other fields from schema
    ]);
    
    // Relationships via API or query
    // API: GET /api/crud6/sales_order/{id}/lines
    // Or: $linesModel->where('order_id', $orderId)->get()
}
```

## 🔌 API Endpoints

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

### Payments
- List: `GET /api/crud6/payment`
- Create: `POST /api/crud6/payment`
- Read: `GET /api/crud6/payment/{id}`
- Update: `PUT /api/crud6/payment/{id}`
- Delete: `DELETE /api/crud6/payment/{id}`
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
- Product Catalog Relationships: `GET /api/crud6/catalog/{id}/product_catalog`

### Custom Payment Endpoints
- Process Payment: `POST /api/payment/process`
- Webhook handlers for payment gateways

## 💳 Payment Gateway Configuration

Configure payment gateway credentials in your `.env` file:

```env
# Stripe
STRIPE_PUBLIC_KEY=your_stripe_public_key
STRIPE_SECRET_KEY=your_stripe_secret_key
STRIPE_WEBHOOK_SECRET=your_stripe_webhook_secret

# PayPal
PAYPAL_CLIENT_ID=your_paypal_client_id
PAYPAL_CLIENT_SECRET=your_paypal_client_secret
PAYPAL_MODE=sandbox

# Apple Pay
APPLE_PAY_MERCHANT_ID=your_apple_pay_merchant_id
APPLE_PAY_CERTIFICATE_PATH=/path/to/certificate

# Google Pay
GOOGLE_PAY_MERCHANT_ID=your_google_pay_merchant_id
GOOGLE_PAY_MERCHANT_NAME=Your Business Name
```

## 🎨 Frontend Assets

This sprinkle includes Vue.js components for:
- **Mini Cart** (`app/assets/components/Cart/MiniCart.vue`)
- **Cart Composables** (`app/assets/composables/useCart.ts`)
- **Payment Widget** (`app/assets/js/payment-widget.js`)

## 🧪 Testing

Run tests using:
```bash
composer test
```

Test suites:
- Unit Tests: `app/tests/Unit/`
- Routes: `app/tests/Routes/`
- Schema: `app/tests/Schema/`

## 📝 Namespace

All PHP classes use the namespace: `UserFrosting\Sprinkle\Commerce`

## 🔄 Migration Versions

Migrations are organized by version:
- **v100**: Payment-related tables
- **v401**: Product catalog tables

Migrations are registered in the `Commerce::getMigrations()` method.
