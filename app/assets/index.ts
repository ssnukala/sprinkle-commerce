/**
 * Commerce Sprinkle - Vue.js Assets
 *
 * Main entry point for Commerce sprinkle frontend components
 *
 * This module exposes:
 * - a default export that is a Vue plugin (so `app.use(Commerce)` works)
 * - named exports for components/composables/interfaces/pages/routes
 */

/**
 * Default plugin export so other parts of the app can `app.use(Commerce)`.
 * Currently this plugin is a no-op — most sprinkles register Vue global
 * components or plugins here. If you later add a client-side plugin
 * (e.g. for cart state), register it in this install function.
 */
export default {
    install: (app: App, options?: Record<string, any>) => {
        // Placeholder - no global registration required yet.
        // If you need to register global components or provide plugin-level
        // setup, do it here. Example:
        // import CommercePlugin from './plugins/commerce'
        // app.use(CommercePlugin, options)
    }
}

// Named exports for local consumption
export * from './components'
export * from './composables'
export * from './interfaces'
export * from './pages'
export * from './routes'
