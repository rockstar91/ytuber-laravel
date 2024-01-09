import Vue from 'vue'
import Router from 'vue-router'
import { normalizeURL, decode } from 'ufo'
import { interopDefault } from './utils'
import scrollBehavior from './router.scrollBehavior.js'

const _6257ffd0 = () => interopDefault(import('..\\pages\\api\\index.vue' /* webpackChunkName: "pages/api/index" */))
const _ca1e2bf6 = () => interopDefault(import('..\\pages\\articles\\index.vue' /* webpackChunkName: "pages/articles/index" */))
const _80c01090 = () => interopDefault(import('..\\pages\\captcha\\index.vue' /* webpackChunkName: "pages/captcha/index" */))
const _655e760a = () => interopDefault(import('..\\pages\\chat\\index.vue' /* webpackChunkName: "pages/chat/index" */))
const _2073995e = () => interopDefault(import('..\\pages\\dashboard\\index.vue' /* webpackChunkName: "pages/dashboard/index" */))
const _da18b43c = () => interopDefault(import('..\\pages\\index.vue' /* webpackChunkName: "pages/index" */))
const _7c08b99c = () => interopDefault(import('..\\pages\\faq\\index.vue' /* webpackChunkName: "pages/faq/index" */))
const _a00a9d2e = () => interopDefault(import('..\\pages\\login\\index.vue' /* webpackChunkName: "pages/login/index" */))
const _58d48f8b = () => interopDefault(import('..\\pages\\password-recovery\\index.vue' /* webpackChunkName: "pages/password-recovery/index" */))
const _24f53715 = () => interopDefault(import('..\\pages\\payments\\index.vue' /* webpackChunkName: "pages/payments/index" */))
const _13835cf9 = () => interopDefault(import('..\\pages\\privacy_policy\\index.vue' /* webpackChunkName: "pages/privacy_policy/index" */))
const _6ec3ed29 = () => interopDefault(import('..\\pages\\profile\\index.vue' /* webpackChunkName: "pages/profile/index" */))
const _17976a3c = () => interopDefault(import('..\\pages\\referrals\\index.vue' /* webpackChunkName: "pages/referrals/index" */))
const _2dc69bee = () => interopDefault(import('..\\pages\\registration\\index.vue' /* webpackChunkName: "pages/registration/index" */))
const _2b6919ca = () => interopDefault(import('..\\pages\\rules\\index.vue' /* webpackChunkName: "pages/rules/index" */))
const _ae9e01aa = () => interopDefault(import('..\\pages\\terms_of_use\\index.vue' /* webpackChunkName: "pages/terms_of_use/index" */))
const _beb85026 = () => interopDefault(import('..\\pages\\transactions\\index.vue' /* webpackChunkName: "pages/transactions/index" */))
const _5b203d78 = () => interopDefault(import('..\\pages\\views\\index.vue' /* webpackChunkName: "pages/views/index" */))
const _4a929a88 = () => interopDefault(import('..\\pages\\payments\\success\\index.vue' /* webpackChunkName: "pages/payments/success/index" */))
const _331eb0c1 = () => interopDefault(import('..\\pages\\task\\create\\index.vue' /* webpackChunkName: "pages/task/create/index" */))
const _71c6e09f = () => interopDefault(import('..\\pages\\task\\list\\index.vue' /* webpackChunkName: "pages/task/list/index" */))
const _74c11893 = () => interopDefault(import('..\\pages\\work\\list\\index.vue' /* webpackChunkName: "pages/work/list/index" */))
const _48f3ca4a = () => interopDefault(import('..\\pages\\task\\edit\\_id.vue' /* webpackChunkName: "pages/task/edit/_id" */))
const _ef7cec8a = () => interopDefault(import('..\\pages\\allow-transaction\\_alias.vue' /* webpackChunkName: "pages/allow-transaction/_alias" */))
const _a1820b88 = () => interopDefault(import('..\\pages\\refferal\\_alias.vue' /* webpackChunkName: "pages/refferal/_alias" */))

const emptyFn = () => {}

Vue.use(Router)

export const routerOptions = {
  mode: 'history',
  base: '/',
  linkActiveClass: 'nuxt-link-active',
  linkExactActiveClass: 'nuxt-link-exact-active',
  scrollBehavior,

  routes: [{
    path: "/api",
    component: _6257ffd0,
    name: "api___ru"
  }, {
    path: "/articles",
    component: _ca1e2bf6,
    name: "articles___ru"
  }, {
    path: "/captcha",
    component: _80c01090,
    name: "captcha___ru"
  }, {
    path: "/chat",
    component: _655e760a,
    name: "chat___ru"
  }, {
    path: "/dashboard",
    component: _2073995e,
    name: "dashboard___ru"
  }, {
    path: "/en",
    component: _da18b43c,
    name: "index___en"
  }, {
    path: "/faq",
    component: _7c08b99c,
    name: "faq___ru"
  }, {
    path: "/login",
    component: _a00a9d2e,
    name: "login___ru"
  }, {
    path: "/password-recovery",
    component: _58d48f8b,
    name: "password-recovery___ru"
  }, {
    path: "/payments",
    component: _24f53715,
    name: "payments___ru"
  }, {
    path: "/privacy_policy",
    component: _13835cf9,
    name: "privacy_policy___ru"
  }, {
    path: "/profile",
    component: _6ec3ed29,
    name: "profile___ru"
  }, {
    path: "/referrals",
    component: _17976a3c,
    name: "referrals___ru"
  }, {
    path: "/registration",
    component: _2dc69bee,
    name: "registration___ru"
  }, {
    path: "/rules",
    component: _2b6919ca,
    name: "rules___ru"
  }, {
    path: "/terms_of_use",
    component: _ae9e01aa,
    name: "terms_of_use___ru"
  }, {
    path: "/transactions",
    component: _beb85026,
    name: "transactions___ru"
  }, {
    path: "/views",
    component: _5b203d78,
    name: "views___ru"
  }, {
    path: "/en/api",
    component: _6257ffd0,
    name: "api___en"
  }, {
    path: "/en/articles",
    component: _ca1e2bf6,
    name: "articles___en"
  }, {
    path: "/en/captcha",
    component: _80c01090,
    name: "captcha___en"
  }, {
    path: "/en/chat",
    component: _655e760a,
    name: "chat___en"
  }, {
    path: "/en/dashboard",
    component: _2073995e,
    name: "dashboard___en"
  }, {
    path: "/en/faq",
    component: _7c08b99c,
    name: "faq___en"
  }, {
    path: "/en/login",
    component: _a00a9d2e,
    name: "login___en"
  }, {
    path: "/en/password-recovery",
    component: _58d48f8b,
    name: "password-recovery___en"
  }, {
    path: "/en/payments",
    component: _24f53715,
    name: "payments___en"
  }, {
    path: "/en/privacy_policy",
    component: _13835cf9,
    name: "privacy_policy___en"
  }, {
    path: "/en/profile",
    component: _6ec3ed29,
    name: "profile___en"
  }, {
    path: "/en/referrals",
    component: _17976a3c,
    name: "referrals___en"
  }, {
    path: "/en/registration",
    component: _2dc69bee,
    name: "registration___en"
  }, {
    path: "/en/rules",
    component: _2b6919ca,
    name: "rules___en"
  }, {
    path: "/en/terms_of_use",
    component: _ae9e01aa,
    name: "terms_of_use___en"
  }, {
    path: "/en/transactions",
    component: _beb85026,
    name: "transactions___en"
  }, {
    path: "/en/views",
    component: _5b203d78,
    name: "views___en"
  }, {
    path: "/payments/success",
    component: _4a929a88,
    name: "payments-success___ru"
  }, {
    path: "/task/create",
    component: _331eb0c1,
    name: "task-create___ru"
  }, {
    path: "/task/list",
    component: _71c6e09f,
    name: "task-list___ru"
  }, {
    path: "/work/list",
    component: _74c11893,
    name: "work-list___ru"
  }, {
    path: "/en/payments/success",
    component: _4a929a88,
    name: "payments-success___en"
  }, {
    path: "/en/task/create",
    component: _331eb0c1,
    name: "task-create___en"
  }, {
    path: "/en/task/list",
    component: _71c6e09f,
    name: "task-list___en"
  }, {
    path: "/en/work/list",
    component: _74c11893,
    name: "work-list___en"
  }, {
    path: "/en/task/edit/:id?",
    component: _48f3ca4a,
    name: "task-edit-id___en"
  }, {
    path: "/en/allow-transaction/:alias?",
    component: _ef7cec8a,
    name: "allow-transaction-alias___en"
  }, {
    path: "/en/refferal/:alias?",
    component: _a1820b88,
    name: "refferal-alias___en"
  }, {
    path: "/task/edit/:id?",
    component: _48f3ca4a,
    name: "task-edit-id___ru"
  }, {
    path: "/allow-transaction/:alias?",
    component: _ef7cec8a,
    name: "allow-transaction-alias___ru"
  }, {
    path: "/refferal/:alias?",
    component: _a1820b88,
    name: "refferal-alias___ru"
  }, {
    path: "/",
    component: _da18b43c,
    name: "index___ru"
  }],

  fallback: false
}

export function createRouter (ssrContext, config) {
  const base = (config._app && config._app.basePath) || routerOptions.base
  const router = new Router({ ...routerOptions, base  })

  // TODO: remove in Nuxt 3
  const originalPush = router.push
  router.push = function push (location, onComplete = emptyFn, onAbort) {
    return originalPush.call(this, location, onComplete, onAbort)
  }

  const resolve = router.resolve.bind(router)
  router.resolve = (to, current, append) => {
    if (typeof to === 'string') {
      to = normalizeURL(to)
    }
    return resolve(to, current, append)
  }

  return router
}
