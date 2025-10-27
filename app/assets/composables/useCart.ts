/**
 * Cart Composable
 * 
 * Vue 3 composable for managing shopping cart state and operations.
 * Migrated from cart-utilities.js to TypeScript.
 */

import { ref, computed } from 'vue';
import type { Cart, CartLine, CartOrder, CartTotals } from '../interfaces/CartInterface';

export function useCart(userId: number) {
  const cart = ref<Cart>({
    order: {
      id: undefined,
      name: 'UserCart',
      description: 'User Cart',
      user_id: userId,
      order_date: new Date().toISOString(),
      net_amount: 0,
      tax: 0,
      discount: 0,
      epay_commission: 0,
      gross_amount: 0,
      payment_type: '',
      payment_note: '',
      status: 'A',
      type: 'G'
    },
    lines: []
  });

  const isLoading = ref(false);
  const error = ref<string | null>(null);

  /**
   * Calculate cart totals
   */
  const totals = computed<CartTotals>(() => {
    const activeLines = cart.value.lines.filter(line => line.status === 'A');
    
    return activeLines.reduce(
      (acc, line) => ({
        quantity: acc.quantity + line.quantity,
        net_amount: acc.net_amount + line.net_amount,
        gross_amount: acc.gross_amount + line.gross_amount,
        tax: acc.tax + line.tax,
        discount: acc.discount + line.discount
      }),
      { quantity: 0, net_amount: 0, gross_amount: 0, tax: 0, discount: 0 }
    );
  });

  /**
   * Check if cart is empty
   */
  const isEmpty = computed(() => {
    return cart.value.lines.filter(line => line.status === 'A').length === 0;
  });

  /**
   * Format currency
   */
  function formatCurrency(amount: number, precision: number = 2): string {
    return '$' + amount.toFixed(precision).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
  }

  /**
   * Load active cart for user
   */
  async function loadCart(): Promise<void> {
    isLoading.value = true;
    error.value = null;

    try {
      // Use CRUD6 API to get the last active order for this user
      const response = await fetch(`/api/crud6/sales_order?filters[user_id]=${userId}&filters[status]=A&sorts[id]=desc&size=1`);
      
      if (!response.ok) {
        throw new Error('Failed to load cart');
      }

      const data = await response.json();
      
      if (data.rows && data.rows.length > 0) {
        const orderData = data.rows[0];
        cart.value.order = orderData;
        
        // Load line items
        const linesResponse = await fetch(`/api/crud6/sales_order_lines?filters[order_id]=${orderData.id}&filters[status]=A`);
        if (linesResponse.ok) {
          const linesData = await linesResponse.json();
          cart.value.lines = linesData.rows || [];
        }
      }
    } catch (e) {
      error.value = e instanceof Error ? e.message : 'Unknown error loading cart';
      console.error('Error loading cart:', e);
    } finally {
      isLoading.value = false;
    }
  }

  /**
   * Add item to cart
   */
  async function addToCart(item: Partial<CartLine>): Promise<void> {
    const existingLineIndex = cart.value.lines.findIndex(
      line => line.line_no === item.line_no && line.status === 'A'
    );

    if (existingLineIndex >= 0) {
      // Update existing line
      cart.value.lines[existingLineIndex] = {
        ...cart.value.lines[existingLineIndex],
        ...item,
        status: 'A'
      } as CartLine;
    } else {
      // Add new line
      cart.value.lines.push({
        line_no: cart.value.lines.length + 1,
        description: '',
        unit_price: 0,
        quantity: 1,
        net_amount: 0,
        tax: 0,
        discount: 0,
        gross_amount: 0,
        balance_amount: 0,
        status: 'A',
        ...item
      } as CartLine);
    }

    updateTotals();
  }

  /**
   * Remove item from cart
   */
  function removeFromCart(lineNo: number): void {
    const lineIndex = cart.value.lines.findIndex(line => line.line_no === lineNo);
    
    if (lineIndex >= 0) {
      if (cart.value.lines[lineIndex].id) {
        // Mark as removed if it has an ID (exists in database)
        cart.value.lines[lineIndex].status = 'R';
      } else {
        // Remove from array if it's a new line
        cart.value.lines.splice(lineIndex, 1);
      }
    }

    updateTotals();
  }

  /**
   * Update cart totals in order
   */
  function updateTotals(): void {
    const calculated = totals.value;
    cart.value.order.net_amount = calculated.net_amount;
    cart.value.order.gross_amount = calculated.gross_amount;
    cart.value.order.tax = calculated.tax;
    cart.value.order.discount = calculated.discount;

    // If gross amount is 0, set payment type to 'OT' (Other) with note
    if (calculated.gross_amount === 0) {
      cart.value.order.payment_type = 'OT';
      cart.value.order.payment_note = 'No Payment Needed';
    }
  }

  /**
   * Save cart to server
   */
  async function saveCart(): Promise<void> {
    isLoading.value = true;
    error.value = null;

    try {
      const cartData = {
        sales_order: cart.value.order,
        sales_order_lines: cart.value.lines
      };

      const url = cart.value.order.id 
        ? `/api/cart/${userId}/c/${cart.value.order.id}`
        : `/api/cart/${userId}`;
      
      const method = cart.value.order.id ? 'PUT' : 'POST';

      const response = await fetch(url, {
        method,
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(cartData)
      });

      if (!response.ok) {
        throw new Error('Failed to save cart');
      }

      const result = await response.json();
      
      // Update cart with saved data
      if (result.id) {
        cart.value.order.id = result.id;
      }
      if (result.lines) {
        // Update line IDs from server response
        Object.keys(result.lines).forEach(lineNo => {
          const line = cart.value.lines.find(l => l.line_no === parseInt(lineNo));
          if (line && result.lines[lineNo].id) {
            line.id = result.lines[lineNo].id;
          }
        });
      }
    } catch (e) {
      error.value = e instanceof Error ? e.message : 'Unknown error saving cart';
      console.error('Error saving cart:', e);
      throw e;
    } finally {
      isLoading.value = false;
    }
  }

  /**
   * Clear cart
   */
  function clearCart(): void {
    cart.value.lines = [];
    updateTotals();
  }

  return {
    cart,
    totals,
    isEmpty,
    isLoading,
    error,
    formatCurrency,
    loadCart,
    addToCart,
    removeFromCart,
    updateTotals,
    saveCart,
    clearCart
  };
}
