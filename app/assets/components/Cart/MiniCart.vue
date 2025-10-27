<template>
  <div :class="['mini_cart', isEmpty ? 'empty_cart' : 'full_cart']" :id="`${prefix}_micrt_${formId}`">
    <!-- Hidden source field -->
    <input type="hidden" name="source" value="cart" />

    <!-- Cart total section -->
    <div class="cart_total well well-sm" style="margin: 5px 0px">
      <!-- Empty cart icon -->
      <i v-if="isEmpty" class="fa fa-cart-plus no-cart"></i>

      <!-- Cart details when not empty -->
      <div v-else class="cart-details">
        <div class="row">
          <!-- Cart summary -->
          <div class="col-md-2 cart-col">
            <i class="fa fa-shopping-cart">
              <span class="badge cart_qty" style="font-family: sans-serif">{{ totals.quantity }}</span>
            </i>
            <span class="cart_amt">{{ formatCurrency(totals.gross_amount, 0) }}</span>
          </div>

          <!-- Cart title -->
          <div class="col-md-3 cart-desc">
            <span class="cart-message">{{ title || 'Checkout and Complete Transaction' }}</span>
          </div>

          <!-- Cart form fields -->
          <div class="col-md-7 cart-col cart-form-fields">
            <!-- Payment type selection -->
            <div class="form-group">
              <select
                v-if="paymentOptions.length > 1"
                v-model="cart.order.payment_type"
                class="form-control input-base-elem js-select21 payment-type"
                :id="`${prefix}_paytype_${formId}`"
                name="sales_order[payment_type]"
                @change="onPaymentTypeChange"
              >
                <option v-for="option in paymentOptions" :key="option.id" :value="option.id">
                  {{ option.label }}
                </option>
              </select>
              <template v-else-if="paymentOptions.length === 1">
                <strong>{{ paymentOptions[0].label }}</strong>
                <input
                  type="hidden"
                  name="sales_order[payment_type]"
                  :value="paymentOptions[0].id"
                />
              </template>
            </div>

            <!-- Payment note -->
            <div class="form-group">
              <input
                v-model="cart.order.payment_note"
                type="text"
                class="other-payment form-control input-base-elem input-sm payment_note"
                style="width: 100%"
                :id="`${prefix}_pnote_${formId}`"
                placeholder="Payment note..."
                name="sales_order[payment_note]"
                data-label="Payment Note"
              />
            </div>

            <!-- Payment reference (hidden by default) -->
            <div class="form-group">
              <input
                v-model="cart.order.payment_ref"
                type="text"
                class="other-payment form-control input-base-elem input-sm payment_ref hidden"
                style="width: 100%"
                :id="`${prefix}_payref_${formId}`"
                placeholder="Conf ID / CHQ details"
                name="sales_order[payment_ref]"
                data-label="Confirmation ID"
              />
            </div>

            <!-- Payment buttons -->
            <div class="form-group">
              <input type="hidden" name="payment_details" :id="`payment_details_${prefix}_${formId}`" value="" />

              <!-- PayPal Smart Button (when using sprinkle-payment) -->
              <div
                v-if="paymentProvider === 'paypal'"
                class="paypal-smart-button smart-button-container rs-editform-ok"
                :id="`smart-button-container_${prefix}_${formId}`"
              >
                <div style="text-align: center">
                  <div
                    class="paypal_container paypal-button-container"
                    :id="`paypal-button-container_${prefix}_${formId}`"
                  ></div>
                </div>
              </div>

              <!-- Standard checkout button -->
              <button
                v-else
                type="submit"
                class="btn btn-md rs-editform-ok btn-success cart-submit"
                @click="onCheckout"
                :disabled="isLoading"
              >
                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                <span class="cart-checkout">{{ okText || 'Checkout' }}</span>
              </button>

              <!-- Cancel button -->
              <button
                type="reset"
                class="btn btn-md rs-editform-cancel btn-danger"
                @click="onCancel"
              >
                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                <span class="cart-cancel">{{ cancelText || 'Cancel' }}</span>
              </button>
            </div>
          </div>
        </div>

        <!-- Cart details toggle (optional) -->
        <div v-if="hideLines" class="cart_door text-center bg-info" @click="toggleDetails">
          Click here for details <i :class="['fa', 'cart_door_knob', detailsVisible ? 'fa-arrow-circle-up' : 'fa-arrow-circle-down']"></i>
        </div>
      </div>
    </div>

    <!-- Cart line items (if not hidden) -->
    <div v-if="!hideLines || detailsVisible" class="cart-lines">
      <slot name="lines" :lines="cart.lines" :removeItem="removeFromCart"></slot>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue';
import { useCart } from '../../composables/useCart';
import type { PaymentOption } from '../../interfaces/CartInterface';

interface Props {
  userId: number;
  formId?: string;
  title?: string;
  okText?: string;
  cancelText?: string;
  paymentOptions?: PaymentOption[];
  paymentProvider?: 'paypal' | 'stripe' | 'other';
  hideLines?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  formId: 'cart',
  title: 'Checkout and Complete Transaction',
  okText: 'Checkout',
  cancelText: 'Cancel',
  paymentOptions: () => [
    { id: 'PP', label: 'PayPal' },
    { id: 'ST', label: 'Stripe' },
    { id: 'CK', label: 'Check' },
    { id: 'CH', label: 'Cash' },
    { id: 'OT', label: 'Other' }
  ],
  paymentProvider: 'other',
  hideLines: false
});

const emit = defineEmits<{
  (e: 'checkout', cart: any): void;
  (e: 'cancel'): void;
  (e: 'payment-type-change', paymentType: string): void;
}>();

// Generate random prefix for IDs
const prefix = ref(Math.random().toString(36).substring(2, 7));
const detailsVisible = ref(false);

// Use cart composable
const {
  cart,
  totals,
  isEmpty,
  isLoading,
  error,
  formatCurrency,
  loadCart,
  removeFromCart,
  saveCart
} = useCart(props.userId);

// Load cart on mount
onMounted(async () => {
  await loadCart();
});

// Watch for payment type changes when gross amount is 0
watch(() => totals.value.gross_amount, (newGross) => {
  if (newGross === 0) {
    cart.value.order.payment_type = 'OT';
    cart.value.order.payment_note = 'No Payment Needed';
  }
});

function onPaymentTypeChange() {
  emit('payment-type-change', cart.value.order.payment_type || '');
}

async function onCheckout() {
  try {
    await saveCart();
    emit('checkout', cart.value);
  } catch (e) {
    console.error('Checkout error:', e);
  }
}

function onCancel() {
  emit('cancel');
}

function toggleDetails() {
  detailsVisible.value = !detailsVisible.value;
}
</script>

<style scoped>
.mini_cart {
  margin: 10px 0;
}

.empty_cart .cart-details {
  display: none;
}

.full_cart .no-cart {
  display: none;
}

.cart_total {
  background-color: #f5f5f5;
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 10px;
}

.cart_qty {
  background-color: #5bc0de;
  margin-left: 5px;
}

.cart_amt {
  font-weight: bold;
  color: #5cb85c;
  margin-left: 10px;
}

.cart_door {
  cursor: pointer;
  padding: 5px;
  margin-top: 10px;
}

.cart_door:hover {
  background-color: #d9edf7 !important;
}

.hidden {
  display: none;
}
</style>
