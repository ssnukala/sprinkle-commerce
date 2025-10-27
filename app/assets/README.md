# Vue.js Frontend Components

This directory contains the Vue 3 + TypeScript frontend components for the Orders sprinkle, migrated from the legacy jQuery/Twig implementation.

## Overview

The Orders sprinkle provides Vue.js components for shopping cart functionality, integrated with UserFrosting 6's Vue.js ecosystem and CRUD6 for data operations.

## Components

### MiniCart Component

A Vue 3 component that replaces `templates/partials/mini-cart.html.twig`. Features:

- Display cart summary (quantity, total amount)
- Payment method selection
- Payment note and reference fields
- Integration with payment providers (PayPal, Stripe via sprinkle-payment)
- Real-time cart total calculations
- Hide/show cart details

**Usage:**

```vue
<script setup>
import { MiniCart } from '@ssnukala/sprinkle-orders';
</script>

<template>
  <MiniCart
    :user-id="currentUser.id"
    :payment-options="paymentOptions"
    :payment-provider="'paypal'"
    @checkout="handleCheckout"
  >
    <!-- Optional: custom line items display -->
    <template #lines="{ lines, removeItem }">
      <div v-for="line in lines" :key="line.id">
        {{ line.description }} - {{ line.quantity }} x ${{ line.unit_price }}
        <button @click="removeItem(line.line_no)">Remove</button>
      </div>
    </template>
  </MiniCart>
</template>
```

## Composables

### useCart

A Vue 3 composable for cart state management, migrated from `assets/orders/js/cart-utilities.js`.

**Features:**
- Load active cart for user
- Add/remove items
- Calculate totals (net, gross, tax, discount)
- Save cart to server
- Currency formatting

**Usage:**

```typescript
import { useCart } from '@ssnukala/sprinkle-orders';

const {
  cart,
  totals,
  isEmpty,
  isLoading,
  error,
  loadCart,
  addToCart,
  removeFromCart,
  saveCart
} = useCart(userId);

// Load cart
await loadCart();

// Add item
await addToCart({
  line_no: 1,
  description: 'Product A',
  unit_price: 99.99,
  quantity: 2,
  net_amount: 199.98,
  gross_amount: 199.98,
  tax: 0,
  discount: 0,
  balance_amount: 199.98
});

// Save
await saveCart();
```

## Interfaces

### CartInterface

TypeScript interfaces for type-safe cart operations:

- `CartLine` - Individual cart line item
- `CartOrder` - Cart/order header
- `Cart` - Complete cart (order + lines)
- `PaymentOption` - Payment method option
- `CartTotals` - Calculated totals

## Integration with CRUD6

The cart composable uses CRUD6 API endpoints for data operations:

```typescript
// Load cart
GET /api/crud6/sales_order?filters[user_id]={userId}&filters[status]=A

// Load line items
GET /api/crud6/sales_order_lines?filters[order_id]={orderId}&filters[status]=A

// Save (custom endpoint, requires Phase 2 implementation)
POST /api/cart/{userId}
PUT  /api/cart/{userId}/c/{cartId}
```

## Integration with sprinkle-payment

The MiniCart component is designed to integrate with payment provider components:

```vue
<MiniCart
  :payment-provider="'paypal'"
  @checkout="handlePayPalCheckout"
/>
```

When `payment-provider` is set to `'paypal'`, the component will render a PayPal button container that can be initialized by sprinkle-payment's PayPal integration.

## Migration from Legacy Code

### Replaced Files

**Legacy (UF4):**
- `templates/partials/mini-cart.html.twig` → `components/Cart/MiniCart.vue`
- `assets/orders/js/cart-utilities.js` → `composables/useCart.ts`

**Key Improvements:**
- jQuery → Vue 3 reactivity
- Twig templates → Vue SFC (Single File Components)
- JavaScript → TypeScript (type safety)
- Inline scripts → Composables (reusable logic)
- Manual DOM manipulation → Declarative rendering

### Business Logic Migration

The cart business logic from `cart-utilities.js` has been preserved in `useCart.ts`:

- `addCartRow()` → `addToCart()`
- `removeCartRow()` → `removeFromCart()`
- `getCartTotal()` → Reactive `totals` computed property
- `currencyFormat()` → `formatCurrency()`
- `initializeCart()` → Component lifecycle + watchers

## Development

### Building

The Vue components should be built as part of the UserFrosting 6 asset pipeline using Vite.

### Testing

Unit tests should be written using Vitest:

```typescript
import { describe, it, expect } from 'vitest';
import { useCart } from '../composables/useCart';

describe('useCart', () => {
  it('calculates totals correctly', () => {
    const { cart, totals } = useCart(1);
    // ... test logic
  });
});
```

## Next Steps

### Phase 2 Implementation Required

The custom cart API endpoints need to be implemented:

1. **Backend Controllers** (in `app/src/Controller/Cart/`):
   - `AddToCartAction.php`
   - `UpdateCartAction.php`
   - `GetCartAction.php`
   - `RemoveFromCartAction.php`

2. **Routes** (in `app/src/Routes/OrdersRoutes.php`):
   - `POST /api/cart/{user_id}`
   - `PUT /api/cart/{user_id}/c/{cart_id}`
   - `GET /api/cart/{user_id}`
   - `DELETE /api/cart/{user_id}/item/{line_id}`

3. **Payment Integration**:
   - Integrate with sprinkle-payment PayPal/Stripe components
   - Implement webhook handlers
   - Add payment button initialization

See `CART_CHECKOUT_MIGRATION.md` for detailed implementation plan.

## Notes

### Regarding Models

Given CRUD6's generic model system, the custom Eloquent models in `app/src/Database/Models/` are **optional** unless you need:

- Custom relationships beyond what JSON schemas provide
- Custom scopes or query methods
- Business logic methods on the model
- Custom attribute accessors/mutators

For basic CRUD operations, CRUD6's dynamic models are sufficient. The models can be kept for:
1. Code completion/IDE support
2. Custom business logic (e.g., `SalesOrder::lastActiveForUser()`)
3. Type hints in custom controllers

### Payment Provider Integration

The MiniCart component provides a slot for the PayPal/Stripe button container. The actual payment button initialization should be handled by sprinkle-payment components:

```vue
<!-- Example integration with sprinkle-payment -->
<MiniCart :payment-provider="'paypal'">
  <template #payment-button>
    <PayPalSmartButton :amount="cart.order.gross_amount" @approved="handlePayment" />
  </template>
</MiniCart>
```
