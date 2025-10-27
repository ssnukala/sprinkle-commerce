<template>
  <div class="commerce-page">
    <div class="container-fluid">
      <h2>Create Order</h2>

      <!-- Error message -->
      <div v-if="error" class="alert alert-danger" role="alert">
        <button type="button" class="close" @click="error = null">&times;</button>
        {{ error }}
      </div>

      <!-- Success message -->
      <div v-if="successMessage" class="alert alert-success" role="alert">
        <button type="button" class="close" @click="successMessage = null">&times;</button>
        {{ successMessage }}
      </div>

      <!-- Order form -->
      <div class="row">
        <div class="col-md-8">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Add Products to Order</h3>
            </div>
            <div class="panel-body">
              <!-- Product entry form -->
              <form @submit.prevent="addProductToOrder" class="form-inline">
                <div class="form-group">
                  <label for="productId" class="sr-only">Product ID</label>
                  <input
                    id="productId"
                    v-model.number="newItem.productId"
                    type="number"
                    class="form-control"
                    placeholder="Product ID"
                    required
                    min="1"
                  />
                </div>

                <div class="form-group">
                  <label for="quantity" class="sr-only">Quantity</label>
                  <input
                    id="quantity"
                    v-model.number="newItem.quantity"
                    type="number"
                    class="form-control"
                    placeholder="Quantity"
                    required
                    min="1"
                    step="1"
                  />
                </div>

                <button type="submit" class="btn btn-primary" :disabled="isLoading || !newItem.productId || !newItem.quantity">
                  <i class="fa fa-plus"></i> Add Product
                </button>
              </form>

              <!-- Order lines table -->
              <div v-if="orderLines.length > 0" class="order-lines-table" style="margin-top: 20px;">
                <table class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Product</th>
                      <th>Unit Price</th>
                      <th>Quantity</th>
                      <th>Amount</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(line, index) in orderLines" :key="index">
                      <td>{{ index + 1 }}</td>
                      <td>
                        <div v-if="line.product">
                          <strong>{{ line.product.name }}</strong>
                          <br />
                          <small class="text-muted">ID: {{ line.productId }}</small>
                        </div>
                        <div v-else class="text-muted">Product ID: {{ line.productId }}</div>
                      </td>
                      <td>{{ formatCurrency(line.unitPrice) }}</td>
                      <td>
                        <input
                          v-model.number="line.quantity"
                          type="number"
                          class="form-control input-sm"
                          min="1"
                          @change="updateLineTotal(line)"
                          style="width: 80px;"
                        />
                      </td>
                      <td>{{ formatCurrency(line.amount) }}</td>
                      <td>
                        <button
                          @click="removeLine(index)"
                          class="btn btn-danger btn-xs"
                          title="Remove"
                        >
                          <i class="fa fa-trash"></i>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th colspan="4" class="text-right">Total:</th>
                      <th>{{ formatCurrency(orderTotal) }}</th>
                      <th></th>
                    </tr>
                  </tfoot>
                </table>
              </div>

              <!-- Empty state -->
              <div v-else class="alert alert-info" style="margin-top: 20px;">
                <i class="fa fa-info-circle"></i> No products added yet. Enter a product ID and quantity above to get started.
              </div>
            </div>
          </div>
        </div>

        <!-- Checkout panel -->
        <div class="col-md-4">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Checkout</h3>
            </div>
            <div class="panel-body">
              <!-- Order summary -->
              <div class="order-summary">
                <div class="row">
                  <div class="col-xs-6">Items:</div>
                  <div class="col-xs-6 text-right">{{ totalQuantity }}</div>
                </div>
                <div class="row">
                  <div class="col-xs-6"><strong>Total:</strong></div>
                  <div class="col-xs-6 text-right"><strong>{{ formatCurrency(orderTotal) }}</strong></div>
                </div>
              </div>

              <hr />

              <!-- Payment processor selection -->
              <div class="form-group">
                <label for="paymentProcessor">Payment Processor</label>
                <select
                  id="paymentProcessor"
                  v-model="paymentProcessor"
                  class="form-control"
                >
                  <option value="">-- Select Payment Method --</option>
                  <option value="stripe">Stripe</option>
                  <option value="paypal">PayPal</option>
                  <option value="applepay">Apple Pay</option>
                  <option value="googlepay">Google Pay</option>
                  <option value="check">Check</option>
                  <option value="cash">Cash</option>
                </select>
              </div>

              <!-- Payment note -->
              <div class="form-group">
                <label for="paymentNote">Payment Note (Optional)</label>
                <textarea
                  id="paymentNote"
                  v-model="paymentNote"
                  class="form-control"
                  rows="2"
                  placeholder="Add any payment notes..."
                ></textarea>
              </div>

              <!-- Checkout button -->
              <button
                @click="processCheckout"
                :disabled="isLoading || orderLines.length === 0 || !paymentProcessor"
                class="btn btn-success btn-lg btn-block"
              >
                <i v-if="isLoading" class="fa fa-spinner fa-spin"></i>
                <i v-else class="fa fa-credit-card"></i>
                {{ isLoading ? 'Processing...' : 'Proceed to Payment' }}
              </button>

              <!-- Cancel button -->
              <button
                @click="cancelOrder"
                :disabled="isLoading"
                class="btn btn-default btn-block"
                style="margin-top: 10px;"
              >
                <i class="fa fa-times"></i> Cancel
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';

interface Product {
  id: number;
  name: string;
  description?: string;
  price: number;
  [key: string]: any;
}

interface OrderLine {
  productId: number;
  product?: Product;
  quantity: number;
  unitPrice: number;
  amount: number;
}

interface Props {
  userId: number;
}

const props = defineProps<Props>();

const orderLines = ref<OrderLine[]>([]);
const newItem = ref({
  productId: null as number | null,
  quantity: 1
});
const paymentProcessor = ref('');
const paymentNote = ref('');
const isLoading = ref(false);
const error = ref<string | null>(null);
const successMessage = ref<string | null>(null);

// Computed properties
const totalQuantity = computed(() => {
  return orderLines.value.reduce((sum, line) => sum + line.quantity, 0);
});

const orderTotal = computed(() => {
  return orderLines.value.reduce((sum, line) => sum + line.amount, 0);
});

// Methods
function formatCurrency(amount: number): string {
  return '$' + amount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
}

async function fetchProduct(productId: number): Promise<Product | null> {
  try {
    const response = await fetch(`/api/crud6/product/${productId}`);
    if (!response.ok) {
      throw new Error('Product not found');
    }
    const data = await response.json();
    return data;
  } catch (e) {
    console.error('Error fetching product:', e);
    return null;
  }
}

async function addProductToOrder() {
  if (!newItem.value.productId || !newItem.value.quantity) {
    return;
  }

  error.value = null;
  isLoading.value = true;

  try {
    // Fetch product details
    const product = await fetchProduct(newItem.value.productId);
    
    if (!product) {
      throw new Error(`Product with ID ${newItem.value.productId} not found`);
    }

    // Check if product already in cart
    const existingLineIndex = orderLines.value.findIndex(
      line => line.productId === newItem.value.productId
    );

    if (existingLineIndex >= 0) {
      // Update existing line
      orderLines.value[existingLineIndex].quantity += newItem.value.quantity;
      updateLineTotal(orderLines.value[existingLineIndex]);
    } else {
      // Add new line
      const unitPrice = product.price || 0;
      orderLines.value.push({
        productId: newItem.value.productId,
        product: product,
        quantity: newItem.value.quantity,
        unitPrice: unitPrice,
        amount: unitPrice * newItem.value.quantity
      });
    }

    // Reset form
    newItem.value.productId = null;
    newItem.value.quantity = 1;
  } catch (e) {
    error.value = e instanceof Error ? e.message : 'Error adding product';
  } finally {
    isLoading.value = false;
  }
}

function updateLineTotal(line: OrderLine) {
  line.amount = line.unitPrice * line.quantity;
}

function removeLine(index: number) {
  orderLines.value.splice(index, 1);
}

async function processCheckout() {
  if (orderLines.value.length === 0) {
    error.value = 'Please add at least one product to the order';
    return;
  }

  if (!paymentProcessor.value) {
    error.value = 'Please select a payment method';
    return;
  }

  error.value = null;
  successMessage.value = null;
  isLoading.value = true;

  try {
    // Step 1: Create the sales order
    const orderData = {
      name: `Order-${Date.now()}`,
      description: 'Order from Commerce Page',
      user_id: props.userId,
      order_date: new Date().toISOString(),
      net_amount: orderTotal.value,
      tax: 0,
      discount: 0,
      epay_commission: 0,
      gross_amount: orderTotal.value,
      payment_type: mapPaymentProcessorToType(paymentProcessor.value),
      payment_note: paymentNote.value || '',
      status: 'P', // Pending
      type: 'G'
    };

    const orderResponse = await fetch('/api/crud6/sales_order', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(orderData)
    });

    if (!orderResponse.ok) {
      throw new Error('Failed to create order');
    }

    const createdOrder = await orderResponse.json();
    const orderId = createdOrder.id;

    // Step 2: Create order lines
    for (let i = 0; i < orderLines.value.length; i++) {
      const line = orderLines.value[i];
      const lineData = {
        order_id: orderId,
        product_catalog_id: line.productId,
        line_no: i + 1,
        description: line.product?.name || `Product ${line.productId}`,
        unit_price: line.unitPrice,
        quantity: line.quantity,
        net_amount: line.amount,
        tax: 0,
        discount: 0,
        gross_amount: line.amount,
        balance_amount: line.amount,
        status: 'A'
      };

      await fetch('/api/crud6/sales_order_lines', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(lineData)
      });
    }

    successMessage.value = `Order #${orderId} created successfully!`;

    // Step 3: Redirect to payment page based on processor
    setTimeout(() => {
      redirectToPayment(orderId, paymentProcessor.value);
    }, 1500);

  } catch (e) {
    error.value = e instanceof Error ? e.message : 'Error processing checkout';
  } finally {
    isLoading.value = false;
  }
}

function mapPaymentProcessorToType(processor: string): string {
  const mapping: Record<string, string> = {
    'stripe': 'ST',
    'paypal': 'PP',
    'applepay': 'AP',
    'googlepay': 'GP',
    'check': 'CK',
    'cash': 'CH'
  };
  return mapping[processor] || 'OT';
}

function redirectToPayment(orderId: number, processor: string) {
  // Note: This assumes sprinkle-payment is installed and provides these routes
  // If not installed, this will gracefully show a message
  
  const paymentUrls: Record<string, string> = {
    'stripe': `/payment/stripe/${orderId}`,
    'paypal': `/payment/paypal/${orderId}`,
    'applepay': `/payment/applepay/${orderId}`,
    'googlepay': `/payment/googlepay/${orderId}`,
    'check': `/payment/manual/check/${orderId}`,
    'cash': `/payment/manual/cash/${orderId}`
  };

  const paymentUrl = paymentUrls[processor];
  
  if (paymentUrl) {
    window.location.href = paymentUrl;
  } else {
    successMessage.value += ' Payment page will be available when sprinkle-payment is installed.';
    // Clear the order after 3 seconds
    setTimeout(() => {
      cancelOrder();
    }, 3000);
  }
}

function cancelOrder() {
  orderLines.value = [];
  paymentProcessor.value = '';
  paymentNote.value = '';
  error.value = null;
  successMessage.value = null;
}

// Initialize on mount
onMounted(() => {
  // Any initialization if needed
});
</script>

<style scoped>
.commerce-page {
  padding: 20px 0;
}

.order-summary {
  font-size: 16px;
  margin-bottom: 15px;
}

.order-summary .row {
  margin-bottom: 10px;
}

.panel-heading {
  background-color: #337ab7;
  color: white;
}

.panel-primary > .panel-heading {
  background-color: #5cb85c;
  border-color: #4cae4c;
}

.order-lines-table {
  overflow-x: auto;
}

.form-inline .form-group {
  margin-right: 10px;
  margin-bottom: 10px;
}

@media (max-width: 768px) {
  .form-inline .form-group {
    display: block;
    width: 100%;
  }
  
  .form-inline .form-control {
    width: 100%;
  }
  
  .form-inline button {
    width: 100%;
    margin-top: 10px;
  }
}
</style>
