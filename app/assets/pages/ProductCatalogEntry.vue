<script setup lang="ts">
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import type { DetailEditableConfig } from '@ssnukala/sprinkle-crud6/composables'

/**
 * Product Catalog Entry Page
 * 
 * Use Case 2: Product with Catalog Assignments
 * This page demonstrates managing a product and its catalog assignments
 * using the MasterDetailForm component from CRUD6.
 */

const route = useRoute()
const router = useRouter()

// Get product ID from route (if editing)
const productId = computed(() => {
  // Check if we're in edit mode
  if (route.params.id && route.params.id !== 'create') {
    return route.params.id as string
  }
  return undefined
})

// Configure detail section for product catalog assignments
const detailConfig: DetailEditableConfig = {
  model: 'product_catalog',
  foreign_key: 'product_id',
  fields: ['catalog_id', 'name', 'description', 'unit_price', 'tax', 'active_date', 'status'],
  title: 'Catalog Assignments',
  allow_add: true,
  allow_edit: true,
  allow_delete: true
}

// Handle save event
function handleSaved() {
  console.log('[ProductCatalogEntry] Product saved successfully')
  router.push('/commerce/products')
}

// Handle cancel event
function handleCancelled() {
  console.log('[ProductCatalogEntry] Product entry cancelled')
  router.push('/commerce/products')
}
</script>

<template>
  <div class="product-catalog-entry-page">
    <div class="uk-container uk-container-large">
      <!-- Page Header -->
      <div class="uk-margin-medium-bottom">
        <h1 class="uk-heading-small">
          {{ productId ? 'Edit Product' : 'Create New Product' }}
        </h1>
        <p class="uk-text-meta">
          {{ productId 
            ? 'Update the product details and catalog assignments below' 
            : 'Enter product information and assign to catalogs'
          }}
        </p>
      </div>

      <!-- Master-Detail Form -->
      <UFCRUD6MasterDetailForm
        model="product"
        :record-id="productId"
        :detail-config="detailConfig"
        @saved="handleSaved"
        @cancelled="handleCancelled"
      />
    </div>
  </div>
</template>

<style scoped>
.product-catalog-entry-page {
  padding: 2rem 0;
}

.uk-heading-small {
  margin-bottom: 0.5rem;
}
</style>
