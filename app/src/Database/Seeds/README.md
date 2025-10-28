# Commerce Sprinkle Database Seeds

This directory contains database seeders for the Commerce sprinkle, providing sample data for testing commerce functionality.

## Overview

The seeders populate all commerce tables with realistic test data:
- **20 Categories** - Various product categories
- **10 Catalogs** - Product catalogs and collections
- **30 Products** - Sample products across different categories
- **Product-Catalog Relationships** - Products assigned to multiple catalogs
- **5 Sales Orders** - Sample customer orders with line items
- **5 Purchase Orders** - Sample supplier orders with line items

## Data Summary

### Categories (20 records)
- Electronics, Computers, Smartphones, Tablets, Audio, Cameras, Wearables, Gaming, Home Appliances, Office Supplies, Books, Clothing, Sports & Outdoors, Toys & Games, Health & Beauty, Automotive, Garden & Tools, Pet Supplies, Food & Beverages, Jewelry

### Catalogs (10 records)
- Main Catalog, Holiday Sale 2025, Back to School, Summer Collection, Black Friday Deals, Clearance Items, New Arrivals, Best Sellers, Premium Products, Budget Friendly

### Products (30 records)
Sample products including:
- Electronics: Wireless Mouse, USB-C Hub, Webcam HD
- Computers: Gaming Laptop, Business Desktop, Ultrabook Pro
- Smartphones: Smartphone X1, Budget Phone, Premium Flagship
- Audio: Wireless Headphones, Portable Speaker, Gaming Headset
- And many more across all categories

### Sales Orders (5 records)
- SO-2025-001: Electronics purchase ($1,490.36)
- SO-2025-002: Bulk office supplies ($813.98)
- SO-2025-003: Gaming setup - Pending payment ($885.57)
- SO-2025-004: Mobile phone upgrade ($763.99)
- SO-2025-005: Photography equipment - Shipped ($1,295.98)

### Purchase Orders (5 records)
- PO-2025-001: Electronics restock ($15,700.00)
- PO-2025-002: Office furniture ($5,400.00)
- PO-2025-003: Bulk smartphones - Pending ($24,920.00)
- PO-2025-004: Gaming equipment ($8,440.00)
- PO-2025-005: Camera equipment ($12,660.00)

## Running Seeders

### Option 1: Run All Seeders (Recommended)

To seed all commerce tables in the correct order:

```bash
php bakery seed UserFrosting\\Sprinkle\\Commerce\\Database\\Seeds\\CommerceSeeder
```

### Option 2: Run Individual Seeders

You can run seeders individually in this order:

```bash
# 1. Categories (must be first)
php bakery seed UserFrosting\\Sprinkle\\Commerce\\Database\\Seeds\\CategorySeeder

# 2. Catalogs
php bakery seed UserFrosting\\Sprinkle\\Commerce\\Database\\Seeds\\CatalogSeeder

# 3. Products (requires Categories)
php bakery seed UserFrosting\\Sprinkle\\Commerce\\Database\\Seeds\\ProductSeeder

# 4. Product-Catalog relationships (requires Products and Catalogs)
php bakery seed UserFrosting\\Sprinkle\\Commerce\\Database\\Seeds\\ProductCatalogSeeder

# 5. Sales Orders
php bakery seed UserFrosting\\Sprinkle\\Commerce\\Database\\Seeds\\SalesOrderSeeder

# 6. Sales Order Lines (requires Sales Orders)
php bakery seed UserFrosting\\Sprinkle\\Commerce\\Database\\Seeds\\SalesOrderLinesSeeder

# 7. Purchase Orders
php bakery seed UserFrosting\\Sprinkle\\Commerce\\Database\\Seeds\\PurchaseOrderSeeder

# 8. Purchase Order Lines (requires Purchase Orders)
php bakery seed UserFrosting\\Sprinkle\\Commerce\\Database\\Seeds\\PurchaseOrderLinesSeeder
```

## Important Notes

1. **Run migrations first**: Make sure you've run all migrations before seeding:
   ```bash
   php bakery migrate
   ```

2. **Foreign Key Constraints**: The seeders must be run in the order specified to respect foreign key relationships.

3. **User ID**: All seeded data uses `user_id = 1`. Make sure you have at least one user in the `users` table before running the seeders.

4. **Idempotency**: These seeders are not idempotent. Running them multiple times will create duplicate data. Clear tables before re-seeding if needed.

5. **Test Data**: This data is for testing purposes only. Do not use in production environments.

## Clearing Seed Data

To remove all seed data, you can truncate the tables in reverse order:

```sql
TRUNCATE TABLE or_purchase_order_lines;
TRUNCATE TABLE or_purchase_order;
TRUNCATE TABLE or_sales_order_lines;
TRUNCATE TABLE or_sales_order;
TRUNCATE TABLE pr_product_catalog;
TRUNCATE TABLE pr_product;
TRUNCATE TABLE pr_catalog;
TRUNCATE TABLE pr_category;
```

Or simply rollback and re-run migrations:

```bash
php bakery migrate:rollback
php bakery migrate
```

## Data Relationships

The seeded data demonstrates:
- **One-to-Many**: Categories → Products, Orders → Order Lines
- **Many-to-Many**: Products ↔ Catalogs (via product_catalog pivot table)
- **Order Statuses**: Mix of completed ('A'), pending ('P'), and shipped orders
- **Payment Methods**: Credit card, PayPal, invoice, wire transfer
- **Line Item Types**: Products, services, and fees

## Testing Commerce Features

With this seed data, you can test:
- Product browsing and filtering by category
- Catalog management and product assignments
- Sales order creation and management
- Purchase order tracking
- Order line item calculations (subtotal, tax, discount)
- Different order statuses and workflows
- Payment processing integration points
