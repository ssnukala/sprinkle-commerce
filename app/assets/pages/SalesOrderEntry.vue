<script setup lang="ts">
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import type { DetailEditableConfig } from '@ssnukala/sprinkle-crud6/composables'

/**
 * Sales Order Entry Page
 * 
 * Use Case 1: Order Entry with Line Items (One-to-Many)
 * This page demonstrates creating/editing sales orders with their line items
 * using the MasterDetailForm component from CRUD6.
 */

const route = useRoute()
const router = useRouter()

// Get order ID from route (if editing)
const orderId = computed(() => {
  // Check if we're in edit mode (route has :id param and it's not 'create')
  if (route.params.id && route.params.id !== 'create') {
    return route.params.id as string
  }
  return undefined
})

// Configure detail section for sales order line items
const detailConfig: DetailEditableConfig = {
  model: 'sales_order_lines',
  foreign_key: 'order_id',
  fields: ['line_no', 'description', 'product_catalog_id', 'unit_price', 'quantity', 'net_amount', 'tax', 'discount', 'gross_amount'],
  title: 'Order Line Items',
  allow_add: true,
  allow_edit: true,
  allow_delete: true
}

// Handle save event
function handleSaved() {
  console.log('[SalesOrderEntry] Order saved successfully')
  router.push('/commerce/sales-orders')
}

// Handle cancel event
function handleCancelled() {
  console.log('[SalesOrderEntry] Order entry cancelled')
  router.push('/commerce/sales-orders')
}
</script>

<template>
  <div class="sales-order-entry-page">
    <div class="uk-container uk-container-large">
      <!-- Page Header -->
      <div class="uk-margin-medium-bottom">
        <h1 class="uk-heading-small">
          {{ orderId ? 'Edit Sales Order' : 'Create New Sales Order' }}
        </h1>
        <p class="uk-text-meta">
          {{ orderId 
            ? 'Update the sales order details and line items below' 
            : 'Enter sales order information and add line items'
          }}
        </p>
      </div>

      <!-- Master-Detail Form -->
      <UFCRUD6MasterDetailForm
        model="sales_order"
        :record-id="orderId"
        :detail-config="detailConfig"
        @saved="handleSaved"
        @cancelled="handleCancelled"
      />
    </div>
  </div>
</template>

<style scoped>
.sales-order-entry-page {
  padding: 2rem 0;
}

.uk-heading-small {
  margin-bottom: 0.5rem;
}
</style>
