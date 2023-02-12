import Vue from 'vue'
import Router from 'vue-router'
import { normalizeURL, decode } from 'ufo'
import { interopDefault } from './utils'
import scrollBehavior from './router.scrollBehavior.js'

const _1284c59d = () => interopDefault(import('..\\pages\\api\\index.vue' /* webpackChunkName: "pages/api/index" */))
const _32e7cfa0 = () => interopDefault(import('..\\pages\\articles\\index.vue' /* webpackChunkName: "pages/articles/index" */))
const _ea8f1a86 = () => interopDefault(import('..\\pages\\captcha\\index.vue' /* webpackChunkName: "pages/captcha/index" */))
const _d0733db6 = () => interopDefault(import('..\\pages\\chat\\index.vue' /* webpackChunkName: "pages/chat/index" */))
const _f14d31ba = () => interopDefault(import('..\\pages\\dashboard\\index.vue' /* webpackChunkName: "pages/dashboard/index" */))
const _fff67d86 = () => interopDefault(import('..\\pages\\index.vue' /* webpackChunkName: "pages/index" */))
const _808d01be = () => interopDefault(import('..\\pages\\faq\\index.vue' /* webpackChunkName: "pages/faq/index" */))
const _6adfaca4 = () => interopDefault(import('..\\pages\\login\\index.vue' /* webpackChunkName: "pages/login/index" */))
const _1f726f60 = () => interopDefault(import('..\\pages\\password-recovery\\index.vue' /* webpackChunkName: "pages/password-recovery/index" */))
const _8627c6a0 = () => interopDefault(import('..\\pages\\payments\\index.vue' /* webpackChunkName: "pages/payments/index" */))
const _55606254 = () => interopDefault(import('..\\pages\\privacy_policy\\index.vue' /* webpackChunkName: "pages/privacy_policy/index" */))
const _39dc682e = () => interopDefault(import('..\\pages\\profile\\index.vue' /* webpackChunkName: "pages/profile/index" */))
const _7e7d3801 = () => interopDefault(import('..\\pages\\referrals\\index.vue' /* webpackChunkName: "pages/referrals/index" */))
const _39a15224 = () => interopDefault(import('..\\pages\\registration\\index.vue' /* webpackChunkName: "pages/registration/index" */))
const _04e0eb60 = () => interopDefault(import('..\\pages\\rules\\index.vue' /* webpackChunkName: "pages/rules/index" */))
const _0d94c174 = () => interopDefault(import('..\\pages\\terms_of_use\\index.vue' /* webpackChunkName: "pages/terms_of_use/index" */))
const _1daf0ff0 = () => interopDefault(import('..\\pages\\transactions\\index.vue' /* webpackChunkName: "pages/transactions/index" */))
const _25f54cee = () => interopDefault(import('..\\pages\\views\\index.vue' /* webpackChunkName: "pages/views/index" */))
const _7228eb81 = () => interopDefault(import('..\\pages\\payments\\success\\index.vue' /* webpackChunkName: "pages/payments/success/index" */))
const _f8b95e48 = () => interopDefault(import('..\\pages\\task\\create\\index.vue' /* webpackChunkName: "pages/task/create/index" */))
const _30ca690c = () => interopDefault(import('..\\pages\\task\\list\\index.vue' /* webpackChunkName: "pages/task/list/index" */))
const _2ad5f924 = () => interopDefault(import('..\\pages\\work\\list\\index.vue' /* webpackChunkName: "pages/work/list/index" */))
const _737d0076 = () => interopDefault(import('..\\pages\\task\\edit\\_id.vue' /* webpackChunkName: "pages/task/edit/_id" */))
const _41d32cd4 = () => interopDefault(import('..\\pages\\allow-transaction\\_alias.vue' /* webpackChunkName: "pages/allow-transaction/_alias" */))
const _1624c801 = () => interopDefault(import('..\\pages\\refferal\\_alias.vue' /* webpackChunkName: "pages/refferal/_alias" */))

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
    component: _1284c59d,
    name: "api___ru"
  }, {
    path: "/articles",
    component: _32e7cfa0,
    name: "articles___ru"
  }, {
    path: "/captcha",
    component: _ea8f1a86,
    name: "captcha___ru"
  }, {
    path: "/chat",
    component: _d0733db6,
    name: "chat___ru"
  }, {
    path: "/dashboard",
    component: _f14d31ba,
    name: "dashboard___ru"
  }, {
    path: "/en",
    component: _fff67d86,
    name: "index___en"
  }, {
    path: "/faq",
    component: _808d01be,
    name: "faq___ru"
  }, {
    path: "/login",
    component: _6adfaca4,
    name: "login___ru"
  }, {
    path: "/password-recovery",
    component: _1f726f60,
    name: "password-recovery___ru"
  }, {
    path: "/payments",
    component: _8627c6a0,
    name: "payments___ru"
  }, {
    path: "/privacy_policy",
    component: _55606254,
    name: "privacy_policy___ru"
  }, {
    path: "/profile",
    component: _39dc682e,
    name: "profile___ru"
  }, {
    path: "/referrals",
    component: _7e7d3801,
    name: "referrals___ru"
  }, {
    path: "/registration",
    component: _39a15224,
    name: "registration___ru"
  }, {
    path: "/rules",
    component: _04e0eb60,
    name: "rules___ru"
  }, {
    path: "/terms_of_use",
    component: _0d94c174,
    name: "terms_of_use___ru"
  }, {
    path: "/transactions",
    component: _1daf0ff0,
    name: "transactions___ru"
  }, {
    path: "/views",
    component: _25f54cee,
    name: "views___ru"
  }, {
    path: "/en/api",
    component: _1284c59d,
    name: "api___en"
  }, {
    path: "/en/articles",
    component: _32e7cfa0,
    name: "articles___en"
  }, {
    path: "/en/captcha",
    component: _ea8f1a86,
    name: "captcha___en"
  }, {
    path: "/en/chat",
    component: _d0733db6,
    name: "chat___en"
  }, {
    path: "/en/dashboard",
    component: _f14d31ba,
    name: "dashboard___en"
  }, {
    path: "/en/faq",
    component: _808d01be,
    name: "faq___en"
  }, {
    path: "/en/login",
    component: _6adfaca4,
    name: "login___en"
  }, {
    path: "/en/password-recovery",
    component: _1f726f60,
    name: "password-recovery___en"
  }, {
    path: "/en/payments",
    component: _8627c6a0,
    name: "payments___en"
  }, {
    path: "/en/privacy_policy",
    component: _55606254,
    name: "privacy_policy___en"
  }, {
    path: "/en/profile",
    component: _39dc682e,
    name: "profile___en"
  }, {
    path: "/en/referrals",
    component: _7e7d3801,
    name: "referrals___en"
  }, {
    path: "/en/registration",
    component: _39a15224,
    name: "registration___en"
  }, {
    path: "/en/rules",
    component: _04e0eb60,
    name: "rules___en"
  }, {
    path: "/en/terms_of_use",
    component: _0d94c174,
    name: "terms_of_use___en"
  }, {
    path: "/en/transactions",
    component: _1daf0ff0,
    name: "transactions___en"
  }, {
    path: "/en/views",
    component: _25f54cee,
    name: "views___en"
  }, {
    path: "/payments/success",
    component: _7228eb81,
    name: "payments-success___ru"
  }, {
    path: "/task/create",
    component: _f8b95e48,
    name: "task-create___ru"
  }, {
    path: "/task/list",
    component: _30ca690c,
    name: "task-list___ru"
  }, {
    path: "/work/list",
    component: _2ad5f924,
    name: "work-list___ru"
  }, {
    path: "/en/payments/success",
    component: _7228eb81,
    name: "payments-success___en"
  }, {
    path: "/en/task/create",
    component: _f8b95e48,
    name: "task-create___en"
  }, {
    path: "/en/task/list",
    component: _30ca690c,
    name: "task-list___en"
  }, {
    path: "/en/work/list",
    component: _2ad5f924,
    name: "work-list___en"
  }, {
    path: "/en/task/edit/:id?",
    component: _737d0076,
    name: "task-edit-id___en"
  }, {
    path: "/en/allow-transaction/:alias?",
    component: _41d32cd4,
    name: "allow-transaction-alias___en"
  }, {
    path: "/en/refferal/:alias?",
    component: _1624c801,
    name: "refferal-alias___en"
  }, {
    path: "/task/edit/:id?",
    component: _737d0076,
    name: "task-edit-id___ru"
  }, {
    path: "/allow-transaction/:alias?",
    component: _41d32cd4,
    name: "allow-transaction-alias___ru"
  }, {
    path: "/refferal/:alias?",
    component: _1624c801,
    name: "refferal-alias___ru"
  }, {
    path: "/",
    component: _fff67d86,
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
