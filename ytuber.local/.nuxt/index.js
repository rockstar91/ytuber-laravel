import Vue from 'vue'
import Vuex from 'vuex'
import Meta from 'vue-meta'
import ClientOnly from 'vue-client-only'
import NoSsr from 'vue-no-ssr'
import { createRouter } from './router.js'
import NuxtChild from './components/nuxt-child.js'
import NuxtError from './components/nuxt-error.vue'
import Nuxt from './components/nuxt.js'
import App from './App.js'
import { setContext, getLocation, getRouteData, normalizeError } from './utils'
import { createStore } from './store.js'

/* Plugins */

import nuxt_plugin_plugin_44daf2da from 'nuxt_plugin_plugin_44daf2da' // Source: .\\components\\plugin.js (mode: 'all')
import nuxt_plugin_bootstrapvue_5621ef2c from 'nuxt_plugin_bootstrapvue_5621ef2c' // Source: .\\bootstrap-vue.js (mode: 'all')
import nuxt_plugin_googleanalytics_97516bee from 'nuxt_plugin_googleanalytics_97516bee' // Source: .\\google-analytics.js (mode: 'client')
import nuxt_plugin_pluginutils_0cafabde from 'nuxt_plugin_pluginutils_0cafabde' // Source: .\\nuxt-i18n\\plugin.utils.js (mode: 'all')
import nuxt_plugin_pluginrouting_337311c8 from 'nuxt_plugin_pluginrouting_337311c8' // Source: .\\nuxt-i18n\\plugin.routing.js (mode: 'all')
import nuxt_plugin_pluginmain_0341ac5a from 'nuxt_plugin_pluginmain_0341ac5a' // Source: .\\nuxt-i18n\\plugin.main.js (mode: 'all')
import nuxt_plugin_toast_6ac4aaac from 'nuxt_plugin_toast_6ac4aaac' // Source: .\\toast.js (mode: 'client')
import nuxt_plugin_recaptcha_3457a5ec from 'nuxt_plugin_recaptcha_3457a5ec' // Source: .\\recaptcha.js (mode: 'all')
import nuxt_plugin_clipboard_f9800a46 from 'nuxt_plugin_clipboard_f9800a46' // Source: .\\clipboard.js (mode: 'client')
import nuxt_plugin_axios_c2d74406 from 'nuxt_plugin_axios_c2d74406' // Source: .\\axios.js (mode: 'all')
import nuxt_plugin_moment_42a2f33b from 'nuxt_plugin_moment_42a2f33b' // Source: .\\moment.js (mode: 'all')
import nuxt_plugin_pagination_0c55d867 from 'nuxt_plugin_pagination_0c55d867' // Source: ..\\plugins\\pagination.js (mode: 'client')
import nuxt_plugin_multiselect_25a96358 from 'nuxt_plugin_multiselect_25a96358' // Source: ..\\plugins\\multiselect.js (mode: 'client')
import nuxt_plugin_youtube_eb95502c from 'nuxt_plugin_youtube_eb95502c' // Source: ..\\plugins\\youtube.js (mode: 'client')
import nuxt_plugin_clickoutside_5b0e370d from 'nuxt_plugin_clickoutside_5b0e370d' // Source: ..\\plugins\\click-outside.js (mode: 'client')
import nuxt_plugin_chart_48b5b2cf from 'nuxt_plugin_chart_48b5b2cf' // Source: ..\\plugins\\chart.js (mode: 'client')
import nuxt_plugin_echo_559e4e2a from 'nuxt_plugin_echo_559e4e2a' // Source: ..\\plugins\\echo (mode: 'client')
import nuxt_plugin_emojiPicker_9e94076e from 'nuxt_plugin_emojiPicker_9e94076e' // Source: ..\\plugins\\emojiPicker.js (mode: 'client')
import nuxt_plugin_emojimartvue2_d0d9ee94 from 'nuxt_plugin_emojimartvue2_d0d9ee94' // Source: ..\\plugins\\emoji-mart-vue-2.js (mode: 'client')
import nuxt_plugin_chatscroll_5f319b1b from 'nuxt_plugin_chatscroll_5f319b1b' // Source: ..\\plugins\\chat-scroll.js (mode: 'client')
import nuxt_plugin_anime_561b90c9 from 'nuxt_plugin_anime_561b90c9' // Source: ..\\plugins\\anime.js (mode: 'client')
import nuxt_plugin_bcrypt_7c3a3925 from 'nuxt_plugin_bcrypt_7c3a3925' // Source: ..\\plugins\\bcrypt.js (mode: 'client')
import nuxt_plugin_auth_c505d0da from 'nuxt_plugin_auth_c505d0da' // Source: .\\auth.js (mode: 'all')

// Component: <ClientOnly>
Vue.component(ClientOnly.name, ClientOnly)

// TODO: Remove in Nuxt 3: <NoSsr>
Vue.component(NoSsr.name, {
  ...NoSsr,
  render (h, ctx) {
    if (process.client && !NoSsr._warned) {
      NoSsr._warned = true

      console.warn('<no-ssr> has been deprecated and will be removed in Nuxt 3, please use <client-only> instead')
    }
    return NoSsr.render(h, ctx)
  }
})

// Component: <NuxtChild>
Vue.component(NuxtChild.name, NuxtChild)
Vue.component('NChild', NuxtChild)

// Component NuxtLink is imported in server.js or client.js

// Component: <Nuxt>
Vue.component(Nuxt.name, Nuxt)

Object.defineProperty(Vue.prototype, '$nuxt', {
  get() {
    const globalNuxt = this.$root.$options.$nuxt
    if (process.client && !globalNuxt && typeof window !== 'undefined') {
      return window.$nuxt
    }
    return globalNuxt
  },
  configurable: true
})

Vue.use(Meta, {"keyName":"head","attribute":"data-n-head","ssrAttribute":"data-n-head-ssr","tagIDKeyName":"hid"})

const defaultTransition = {"name":"page","mode":"out-in","appear":false,"appearClass":"appear","appearActiveClass":"appear-active","appearToClass":"appear-to"}

const originalRegisterModule = Vuex.Store.prototype.registerModule

function registerModule (path, rawModule, options = {}) {
  const preserveState = process.client && (
    Array.isArray(path)
      ? !!path.reduce((namespacedState, path) => namespacedState && namespacedState[path], this.state)
      : path in this.state
  )
  return originalRegisterModule.call(this, path, rawModule, { preserveState, ...options })
}

async function createApp(ssrContext, config = {}) {
  const router = await createRouter(ssrContext, config)

  const store = createStore(ssrContext)
  // Add this.$router into store actions/mutations
  store.$router = router

  // Fix SSR caveat https://github.com/nuxt/nuxt.js/issues/3757#issuecomment-414689141
  store.registerModule = registerModule

  // Create Root instance

  // here we inject the router and store to all child components,
  // making them available everywhere as `this.$router` and `this.$store`.
  const app = {
    head: {"title":"ytuber","htmlAttrs":{"lang":"en"},"meta":[{"charset":"utf-8"},{"name":"viewport","content":"width=device-width, initial-scale=1"},{"hid":"description","name":"description","content":""},{"name":"format-detection","content":"telephone=no"}],"link":[{"rel":"icon","type":"image\u002Fx-icon","href":"\u002Ffavicon.ico"},{"rel":"stylesheet","href":"https:\u002F\u002Ffonts.googleapis.com\u002Fcss?family=Roboto:400,300,100,500,700,900"}],"script":[{"src":"\u002Fassets\u002Fjs\u002Fmain\u002Fjquery.min.js","type":"text\u002Fjavascript"},{"src":"\u002Fassets\u002Fjs\u002Fmain\u002Fbootstrap.bundle.min.js","type":"text\u002Fjavascript"},{"src":"\u002Fassets\u002Fjs\u002Fplugins\u002Floaders\u002Fblockui.min.js","type":"text\u002Fjavascript"},{"src":"\u002Fassets\u002Fjs\u002Fcore\u002Fvue-youtube-embed.umd.js","type":"text\u002Fjavascript"},{"src":"\u002Fassets\u002Fjs\u002Fplugins\u002Fforms\u002Fstyling\u002Fswitchery.min.js","type":"text\u002Fjavascript"},{"src":"\u002Fassets\u002Fjs\u002Fplugins\u002Fui\u002Fprism.min.js","type":"text\u002Fjavascript"},{"src":"\u002Fassets\u002Fjs\u002Fplugins\u002Fforms\u002Fstyling\u002Funiform.min.js","type":"text\u002Fjavascript"},{"src":"\u002Fassets\u002Fjs\u002Fapplication.js","type":"text\u002Fjavascript"}],"style":[]},

    store,
    router,
    nuxt: {
      defaultTransition,
      transitions: [defaultTransition],
      setTransitions (transitions) {
        if (!Array.isArray(transitions)) {
          transitions = [transitions]
        }
        transitions = transitions.map((transition) => {
          if (!transition) {
            transition = defaultTransition
          } else if (typeof transition === 'string') {
            transition = Object.assign({}, defaultTransition, { name: transition })
          } else {
            transition = Object.assign({}, defaultTransition, transition)
          }
          return transition
        })
        this.$options.nuxt.transitions = transitions
        return transitions
      },

      err: null,
      dateErr: null,
      error (err) {
        err = err || null
        app.context._errored = Boolean(err)
        err = err ? normalizeError(err) : null
        let nuxt = app.nuxt // to work with @vue/composition-api, see https://github.com/nuxt/nuxt.js/issues/6517#issuecomment-573280207
        if (this) {
          nuxt = this.nuxt || this.$options.nuxt
        }
        nuxt.dateErr = Date.now()
        nuxt.err = err
        // Used in src/server.js
        if (ssrContext) {
          ssrContext.nuxt.error = err
        }
        return err
      }
    },
    ...App
  }

  // Make app available into store via this.app
  store.app = app

  const next = ssrContext ? ssrContext.next : location => app.router.push(location)
  // Resolve route
  let route
  if (ssrContext) {
    route = router.resolve(ssrContext.url).route
  } else {
    const path = getLocation(router.options.base, router.options.mode)
    route = router.resolve(path).route
  }

  // Set context to app.context
  await setContext(app, {
    store,
    route,
    next,
    error: app.nuxt.error.bind(app),
    payload: ssrContext ? ssrContext.payload : undefined,
    req: ssrContext ? ssrContext.req : undefined,
    res: ssrContext ? ssrContext.res : undefined,
    beforeRenderFns: ssrContext ? ssrContext.beforeRenderFns : undefined,
    ssrContext
  })

  function inject(key, value) {
    if (!key) {
      throw new Error('inject(key, value) has no key provided')
    }
    if (value === undefined) {
      throw new Error(`inject('${key}', value) has no value provided`)
    }

    key = '$' + key
    // Add into app
    app[key] = value
    // Add into context
    if (!app.context[key]) {
      app.context[key] = value
    }

    // Add into store
    store[key] = app[key]

    // Check if plugin not already installed
    const installKey = '__nuxt_' + key + '_installed__'
    if (Vue[installKey]) {
      return
    }
    Vue[installKey] = true
    // Call Vue.use() to install the plugin into vm
    Vue.use(() => {
      if (!Object.prototype.hasOwnProperty.call(Vue.prototype, key)) {
        Object.defineProperty(Vue.prototype, key, {
          get () {
            return this.$root.$options[key]
          }
        })
      }
    })
  }

  // Inject runtime config as $config
  inject('config', config)

  if (process.client) {
    // Replace store state before plugins execution
    if (window.__NUXT__ && window.__NUXT__.state) {
      store.replaceState(window.__NUXT__.state)
    }
  }

  // Add enablePreview(previewData = {}) in context for plugins
  if (process.static && process.client) {
    app.context.enablePreview = function (previewData = {}) {
      app.previewData = Object.assign({}, previewData)
      inject('preview', previewData)
    }
  }
  // Plugin execution

  if (typeof nuxt_plugin_plugin_44daf2da === 'function') {
    await nuxt_plugin_plugin_44daf2da(app.context, inject)
  }

  if (typeof nuxt_plugin_bootstrapvue_5621ef2c === 'function') {
    await nuxt_plugin_bootstrapvue_5621ef2c(app.context, inject)
  }

  if (process.client && typeof nuxt_plugin_googleanalytics_97516bee === 'function') {
    await nuxt_plugin_googleanalytics_97516bee(app.context, inject)
  }

  if (typeof nuxt_plugin_pluginutils_0cafabde === 'function') {
    await nuxt_plugin_pluginutils_0cafabde(app.context, inject)
  }

  if (typeof nuxt_plugin_pluginrouting_337311c8 === 'function') {
    await nuxt_plugin_pluginrouting_337311c8(app.context, inject)
  }

  if (typeof nuxt_plugin_pluginmain_0341ac5a === 'function') {
    await nuxt_plugin_pluginmain_0341ac5a(app.context, inject)
  }

  if (process.client && typeof nuxt_plugin_toast_6ac4aaac === 'function') {
    await nuxt_plugin_toast_6ac4aaac(app.context, inject)
  }

  if (typeof nuxt_plugin_recaptcha_3457a5ec === 'function') {
    await nuxt_plugin_recaptcha_3457a5ec(app.context, inject)
  }

  if (process.client && typeof nuxt_plugin_clipboard_f9800a46 === 'function') {
    await nuxt_plugin_clipboard_f9800a46(app.context, inject)
  }

  if (typeof nuxt_plugin_axios_c2d74406 === 'function') {
    await nuxt_plugin_axios_c2d74406(app.context, inject)
  }

  if (typeof nuxt_plugin_moment_42a2f33b === 'function') {
    await nuxt_plugin_moment_42a2f33b(app.context, inject)
  }

  if (process.client && typeof nuxt_plugin_pagination_0c55d867 === 'function') {
    await nuxt_plugin_pagination_0c55d867(app.context, inject)
  }

  if (process.client && typeof nuxt_plugin_multiselect_25a96358 === 'function') {
    await nuxt_plugin_multiselect_25a96358(app.context, inject)
  }

  if (process.client && typeof nuxt_plugin_youtube_eb95502c === 'function') {
    await nuxt_plugin_youtube_eb95502c(app.context, inject)
  }

  if (process.client && typeof nuxt_plugin_clickoutside_5b0e370d === 'function') {
    await nuxt_plugin_clickoutside_5b0e370d(app.context, inject)
  }

  if (process.client && typeof nuxt_plugin_chart_48b5b2cf === 'function') {
    await nuxt_plugin_chart_48b5b2cf(app.context, inject)
  }

  if (process.client && typeof nuxt_plugin_echo_559e4e2a === 'function') {
    await nuxt_plugin_echo_559e4e2a(app.context, inject)
  }

  if (process.client && typeof nuxt_plugin_emojiPicker_9e94076e === 'function') {
    await nuxt_plugin_emojiPicker_9e94076e(app.context, inject)
  }

  if (process.client && typeof nuxt_plugin_emojimartvue2_d0d9ee94 === 'function') {
    await nuxt_plugin_emojimartvue2_d0d9ee94(app.context, inject)
  }

  if (process.client && typeof nuxt_plugin_chatscroll_5f319b1b === 'function') {
    await nuxt_plugin_chatscroll_5f319b1b(app.context, inject)
  }

  if (process.client && typeof nuxt_plugin_anime_561b90c9 === 'function') {
    await nuxt_plugin_anime_561b90c9(app.context, inject)
  }

  if (process.client && typeof nuxt_plugin_bcrypt_7c3a3925 === 'function') {
    await nuxt_plugin_bcrypt_7c3a3925(app.context, inject)
  }

  if (typeof nuxt_plugin_auth_c505d0da === 'function') {
    await nuxt_plugin_auth_c505d0da(app.context, inject)
  }

  // Lock enablePreview in context
  if (process.static && process.client) {
    app.context.enablePreview = function () {
      console.warn('You cannot call enablePreview() outside a plugin.')
    }
  }

  // Wait for async component to be resolved first
  await new Promise((resolve, reject) => {
    // Ignore 404s rather than blindly replacing URL in browser
    if (process.client) {
      const { route } = router.resolve(app.context.route.fullPath)
      if (!route.matched.length) {
        return resolve()
      }
    }
    router.replace(app.context.route.fullPath, resolve, (err) => {
      // https://github.com/vuejs/vue-router/blob/v3.4.3/src/util/errors.js
      if (!err._isRouter) return reject(err)
      if (err.type !== 2 /* NavigationFailureType.redirected */) return resolve()

      // navigated to a different route in router guard
      const unregister = router.afterEach(async (to, from) => {
        if (process.server && ssrContext && ssrContext.url) {
          ssrContext.url = to.fullPath
        }
        app.context.route = await getRouteData(to)
        app.context.params = to.params || {}
        app.context.query = to.query || {}
        unregister()
        resolve()
      })
    })
  })

  return {
    store,
    app,
    router
  }
}

export { createApp, NuxtError }
