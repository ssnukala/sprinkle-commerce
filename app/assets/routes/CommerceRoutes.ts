/**
 * Commerce Routes
 * 
 * Routes for master-detail pages in Commerce sprinkle.
 * Implements two use cases from sprinkle-crud6 PR #130:
 * 1. Sales Order with Line Items (one-to-many)
 * 2. Product/Catalog Assignments (many-to-many via pivot table)
 */
export default [
    {
        path: '/commerce/sales-orders',
        meta: {
            auth: {},
            title: 'Sales Orders',
            description: 'Sales Order Management - Master-Detail Example'
        },
        children: [
            {
                path: '',
                name: 'commerce.sales-orders.list',
                meta: {
                    permission: {
                        slug: 'uri_sales_orders'
                    }
                },
                component: () => import('@ssnukala/sprinkle-crud6/views/PageList.vue'),
                props: () => ({ model: 'sales_order' })
            },
            {
                path: 'create',
                name: 'commerce.sales-orders.create',
                meta: {
                    title: 'Create Sales Order',
                    description: 'Create new sales order with line items',
                    permission: {
                        slug: 'create_sales_order'
                    }
                },
                component: () => import('../pages/SalesOrderEntry.vue')
            },
            {
                path: ':id',
                name: 'commerce.sales-orders.edit',
                meta: {
                    title: 'Edit Sales Order',
                    description: 'Edit sales order with line items',
                    permission: {
                        slug: 'update_sales_order'
                    }
                },
                component: () => import('../pages/SalesOrderEntry.vue')
            }
        ]
    },
    {
        path: '/commerce/products',
        meta: {
            auth: {},
            title: 'Products',
            description: 'Product Management with Catalog Assignments'
        },
        children: [
            {
                path: '',
                name: 'commerce.products.list',
                meta: {
                    permission: {
                        slug: 'uri_products'
                    }
                },
                component: () => import('@ssnukala/sprinkle-crud6/views/PageList.vue'),
                props: () => ({ model: 'product' })
            },
            {
                path: 'create',
                name: 'commerce.products.create',
                meta: {
                    title: 'Create Product',
                    description: 'Create new product with catalog assignments',
                    permission: {
                        slug: 'create_product'
                    }
                },
                component: () => import('../pages/ProductCatalogEntry.vue')
            },
            {
                path: ':id',
                name: 'commerce.products.edit',
                meta: {
                    title: 'Edit Product',
                    description: 'Edit product with catalog assignments',
                    permission: {
                        slug: 'update_product'
                    }
                },
                component: () => import('../pages/ProductCatalogEntry.vue')
            }
        ]
    },
    {
        path: '/commerce/catalogs',
        meta: {
            auth: {},
            title: 'Catalogs',
            description: 'Catalog Management with Product Assignments'
        },
        children: [
            {
                path: '',
                name: 'commerce.catalogs.list',
                meta: {
                    permission: {
                        slug: 'uri_catalogs'
                    }
                },
                component: () => import('@ssnukala/sprinkle-crud6/views/PageList.vue'),
                props: () => ({ model: 'catalog' })
            },
            {
                path: 'create',
                name: 'commerce.catalogs.create',
                meta: {
                    title: 'Create Catalog',
                    description: 'Create new catalog with product assignments',
                    permission: {
                        slug: 'create_catalog'
                    }
                },
                component: () => import('../pages/CatalogProductsEntry.vue')
            },
            {
                path: ':id',
                name: 'commerce.catalogs.edit',
                meta: {
                    title: 'Edit Catalog',
                    description: 'Edit catalog with product assignments',
                    permission: {
                        slug: 'update_catalog'
                    }
                },
                component: () => import('../pages/CatalogProductsEntry.vue')
            }
        ]
    }
]
