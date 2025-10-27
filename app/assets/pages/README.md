# Commerce Pages

This directory contains full-page Vue components for the Commerce sprinkle.

## CommercePage

The main commerce/checkout page that allows users to create orders.

### Features

- Add products to order by entering product ID and quantity
- Automatically fetches product details from CRUD6 API
- Update quantities for order lines
- Remove items from order
- Select payment processor (Stripe, PayPal, Apple Pay, Google Pay, Check, Cash)
- Add optional payment notes
- Create sales order and order lines via CRUD6 API
- Redirect to payment processor page (requires sprinkle-payment)

### Usage

```vue
<template>
  <CommercePage :user-id="currentUserId" />
</template>

<script setup>
import { CommercePage } from '@ssnukala/sprinkle-commerce';

const currentUserId = 1; // Get from your authentication system
</script>
```

### Props

- `userId` (number, required): The ID of the user creating the order

### API Integration

The component uses the CRUD6 API to:

1. **Fetch product details**: `GET /api/crud6/product/{id}`
2. **Create sales order**: `POST /api/crud6/sales_order`
3. **Create order lines**: `POST /api/crud6/sales_order_lines`

### Payment Integration

After creating the order, the component redirects to payment processor pages:

- Stripe: `/payment/stripe/{orderId}`
- PayPal: `/payment/paypal/{orderId}`
- Apple Pay: `/payment/applepay/{orderId}`
- Google Pay: `/payment/googlepay/{orderId}`
- Check: `/payment/manual/check/{orderId}`
- Cash: `/payment/manual/cash/{orderId}`

**Note:** These payment routes are provided by the `sprinkle-payment` package. If it's not installed, the component will display a message and clear the order.

### Dependencies

- Vue 3.3+
- Bootstrap 3 (for styling)
- Font Awesome (for icons)
- CRUD6 API endpoints
- sprinkle-payment (optional, for payment processing)
