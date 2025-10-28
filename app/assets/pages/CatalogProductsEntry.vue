<script setup lang="ts">
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import type { DetailEditableConfig } from '@ssnukala/sprinkle-crud6/composables'

/**
 * Catalog Products Entry Page
 * 
 * Use Case 2: Catalog with Product Assignments
 * This page demonstrates managing a catalog and its product assignments
 * using the MasterDetailForm component from CRUD6.
 */

const route = useRoute()
const router = useRouter()

// Get catalog ID from route (if editing)
const catalogId = computed(() => {
  // Check if we're in edit mode
  if (route.params.id && route.params.id !== 'create') {
    return route.params.id as string
  }
  return undefined
})

// Configure detail section for catalog's products
const detailConfig: DetailEditableConfig = {
  model: 'product_catalog',
  foreign_key: 'catalog_id',
  fields: ['product_id', 'name', 'description', 'unit_price', 'tax', 'active_date', 'status'],
  title: 'Catalog Products',
  allow_add: true,
  allow_edit: true,
  allow_delete: true
}

// Handle save event
function handleSaved() {
  console.log('[CatalogProductsEntry] Catalog saved successfully')
  router.push('/commerce/catalogs')
}

// Handle cancel event
function handleCancelled() {
  console.log('[CatalogProductsEntry] Catalog entry cancelled')
  router.push('/commerce/catalogs')
}
</script>

<template>
  <div class="catalog-products-entry-page">
    <div class="uk-container uk-container-large">
      <!-- Page Header -->
      <div class="uk-margin-medium-bottom">
        <h1 class="uk-heading-small">
          {{ catalogId ? 'Edit Catalog' : 'Create New Catalog' }}
        </h1>
        <p class="uk-text-meta">
          {{ catalogId 
            ? 'Update the catalog details and product assignments below' 
            : 'Enter catalog information and assign products'
          }}
        </p>
      </div>

      <!-- Master-Detail Form -->
      <UFCRUD6MasterDetailForm
        model="catalog"
        :record-id="catalogId"
        :detail-config="detailConfig"
        @saved="handleSaved"
        @cancelled="handleCancelled"
      />
    </div>
  </div>
</template>

<style scoped>
.catalog-products-entry-page {
  padding: 2rem 0;
}

.uk-heading-small {
  margin-bottom: 0.5rem;
}
</style>
