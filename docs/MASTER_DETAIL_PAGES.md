# Master-Detail Pages - Commerce Sprinkle

This document describes the master-detail pages implementation in the Commerce sprinkle, based on [sprinkle-crud6 PR #130](https://github.com/ssnukala/sprinkle-crud6/pull/130).

## Overview

The Commerce sprinkle now includes full master-detail data entry capabilities for:

1. **Use Case 1**: Sales Orders with Line Items (One-to-Many)
2. **Use Case 2**: Product and Catalog Management (Many-to-Many via pivot table)

These pages leverage the `UFCRUD6MasterDetailForm` and `UFCRUD6DetailGrid` components from sprinkle-crud6 to provide inline editing of master records along with their detail records.

## Use Case 1: Sales Order Entry

### Overview
Create and edit sales orders with their line items in a single form. This demonstrates a one-to-many relationship where one sales order has many line items.

### Routes

- **List**: `/commerce/sales-orders` - View all sales orders
- **Create**: `/commerce/sales-orders/create` - Create new order with line items
- **Edit**: `/commerce/sales-orders/:id` - Edit existing order and line items

### Schema Configuration

**sales_order.json** includes:
```json
{
  "detail_editable": {
    "model": "sales_order_lines",
    "foreign_key": "order_id",
    "fields": ["line_no", "description", "product_catalog_id", "unit_price", "quantity", "net_amount", "tax", "discount", "gross_amount"],
    "title": "Order Line Items",
    "allow_add": true,
    "allow_edit": true,
    "allow_delete": true
  }
}
```

### Features

- Create orders with multiple line items in a single transaction
- Inline editing of line items using DetailGrid
- Add, edit, and delete line items
- Automatic foreign key population (order_id)
- All changes saved together when the form is submitted

### Page Component

**SalesOrderEntry.vue** uses:
- `UFCRUD6MasterDetailForm` component
- Configured with sales_order as master and sales_order_lines as detail
- Handles both create and edit modes based on route parameter

## Use Case 2: Product and Catalog Management

### Overview
Manage products and their catalog assignments, or catalogs and their product assignments. This demonstrates managing relationships through a pivot table (product_catalog).

### Routes

#### Product Management
- **List**: `/commerce/products` - View all products
- **Create**: `/commerce/products/create` - Create product with catalog assignments
- **Edit**: `/commerce/products/:id` - Edit product and catalog assignments

#### Catalog Management
- **List**: `/commerce/catalogs` - View all catalogs
- **Create**: `/commerce/catalogs/create` - Create catalog with product assignments
- **Edit**: `/commerce/catalogs/:id` - Edit catalog and product assignments

### Schema Configuration

**product.json** includes:
```json
{
  "detail_editable": {
    "model": "product_catalog",
    "foreign_key": "product_id",
    "fields": ["catalog_id", "name", "description", "unit_price", "tax", "active_date", "status"],
    "title": "Catalog Assignments",
    "allow_add": true,
    "allow_edit": true,
    "allow_delete": true
  }
}
```

**catalog.json** includes:
```json
{
  "detail_editable": {
    "model": "product_catalog",
    "foreign_key": "catalog_id",
    "fields": ["product_id", "name", "description", "unit_price", "tax", "active_date", "status"],
    "title": "Catalog Products",
    "allow_add": true,
    "allow_edit": true,
    "allow_delete": true
  }
}
```

### Features

- Manage products and assign them to multiple catalogs
- Manage catalogs and assign multiple products to them
- Inline editing of product-catalog relationships
- Custom pricing per product-catalog assignment
- Bidirectional management (from product or from catalog)

### Page Components

**ProductCatalogEntry.vue**:
- Manages a product and its catalog assignments
- Uses `UFCRUD6MasterDetailForm` with product as master

**CatalogProductsEntry.vue**:
- Manages a catalog and its product assignments
- Uses `UFCRUD6MasterDetailForm` with catalog as master

## Implementation Details

### Components Used

All pages use the following components from sprinkle-crud6:

1. **UFCRUD6MasterDetailForm** - Main form component
   - Handles master record fields
   - Integrates DetailGrid for inline detail editing
   - Single save operation for master + details
   - Full validation and error handling

2. **UFCRUD6DetailGrid** - Inline editable grid
   - Add/edit/delete operations on detail records
   - Support for all field types
   - Visual feedback for row states
   - Configurable permissions

### Save Operation Flow

When a master-detail form is saved:

1. User fills master record form fields
2. User adds/edits/deletes detail records in inline grid
3. Client-side validation on all fields
4. Single save operation via `useMasterDetail` composable:
   - Save/update master record
   - Process detail records based on `_action` flag:
     - `create`: POST to create new detail record
     - `update`: PUT to update existing detail record
     - `delete`: DELETE to remove detail record
   - Foreign keys automatically set to master ID
   - Return response with operation counts

### Detail Record Actions

Detail records track changes with internal `_action` flags:
- `_action='create'`: New record to be created
- `_action='update'`: Existing record to be updated
- `_action='delete'`: Existing record to be deleted

These flags are automatically managed by the DetailGrid component.

## Permissions

Each route requires appropriate permissions:

### Sales Orders
- `uri_sales_orders` - View orders list
- `create_sales_order` - Create new orders
- `update_sales_order` - Edit existing orders

### Products
- `uri_products` - View products list
- `create_product` - Create new products
- `update_product` - Edit existing products

### Catalogs
- `uri_catalogs` - View catalogs list
- `create_catalog` - Create new catalogs
- `update_catalog` - Edit existing catalogs

## API Endpoints

The pages use the following CRUD6 API endpoints:

### Sales Orders
- `GET /api/crud6/sales_order` - List orders
- `POST /api/crud6/sales_order` - Create order
- `GET /api/crud6/sales_order/{id}` - Get order
- `PUT /api/crud6/sales_order/{id}` - Update order
- `GET /api/crud6/sales_order/{id}/sales_order_lines` - Get order lines
- `POST /api/crud6/sales_order_lines` - Create line item
- `PUT /api/crud6/sales_order_lines/{id}` - Update line item
- `DELETE /api/crud6/sales_order_lines/{id}` - Delete line item

### Products and Catalogs
- Similar patterns for `product`, `catalog`, and `product_catalog` endpoints

## Testing

To test the master-detail pages:

1. **Sales Order Entry**:
   - Navigate to `/commerce/sales-orders/create`
   - Fill in order information
   - Click "Add Row" to add line items
   - Fill in line item details (description, quantity, price)
   - Click "Create Sales Order" to save
   - Verify order and line items are saved

2. **Product Catalog Management**:
   - Navigate to `/commerce/products/create`
   - Fill in product information
   - Click "Add Row" to assign to catalogs
   - Fill in catalog assignment details
   - Click "Create Product" to save
   - Verify product and catalog assignments are saved

3. **Catalog Product Management**:
   - Navigate to `/commerce/catalogs/create`
   - Fill in catalog information
   - Click "Add Row" to add products
   - Fill in product details
   - Click "Create Catalog" to save
   - Verify catalog and product assignments are saved

## Troubleshooting

### Issue: Detail records not saving

**Solution**: Ensure the foreign key field name in the schema's `detail_editable` configuration matches the actual database column.

### Issue: Permission errors

**Solution**: Verify the user has the required permissions in the schema configuration.

### Issue: Fields not showing in detail grid

**Solution**: Check that the fields listed in `detail_editable.fields` exist in the detail schema and are not marked as `auto_increment` or `readonly` (unless they should be read-only).

## See Also

- [sprinkle-crud6 PR #130](https://github.com/ssnukala/sprinkle-crud6/pull/130) - Original implementation
- [sprinkle-crud6 Master-Detail Usage Guide](https://github.com/ssnukala/sprinkle-crud6/blob/main/examples/master-detail-usage.md)
- [sprinkle-crud6 Documentation](https://github.com/ssnukala/sprinkle-crud6)
