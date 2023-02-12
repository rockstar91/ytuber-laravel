

export const Constants = {
  COMPONENT_OPTIONS_KEY: "nuxtI18n",
  STRATEGIES: {"PREFIX":"prefix","PREFIX_EXCEPT_DEFAULT":"prefix_except_default","PREFIX_AND_DEFAULT":"prefix_and_default","NO_PREFIX":"no_prefix"},
  REDIRECT_ON_OPTIONS: {"ALL":"all","ROOT":"root","NO_PREFIX":"no prefix"},
}
export const nuxtOptions = {
  isUniversalMode: true,
  trailingSlash: undefined,
}
export const options = {
  vueI18n: {},
  vueI18nLoader: false,
  locales: [{"code":"en","file":"en_EN.json","name":"English"},{"code":"ru","file":"ru_RU.json","name":"Русский"}],
  defaultLocale: "ru",
  defaultDirection: "ltr",
  routesNameSeparator: "___",
  defaultLocaleRouteNameSuffix: "default",
  sortRoutes: true,
  strategy: "prefix_except_default",
  lazy: true,
  langDir: "F:\\WebServer\\domains\\ytuber-nuxt-laravel\\ytuber.local\\locales",
  rootRedirect: null,
  detectBrowserLanguage: {"alwaysRedirect":false,"cookieCrossOrigin":false,"cookieDomain":null,"cookieKey":"i18n_redirected","cookieSecure":false,"fallbackLocale":"","redirectOn":"root","useCookie":true},
  differentDomains: false,
  baseUrl: "",
  vuex: {"moduleName":"i18n","syncRouteParams":true},
  parsePages: true,
  pages: {},
  skipSettingLocaleOnNavigate: false,
  onBeforeLanguageSwitch: () => {},
  onLanguageSwitched: () => null,
  normalizedLocales: [{"code":"en","file":"en_EN.json","name":"English"},{"code":"ru","file":"ru_RU.json","name":"Русский"}],
  localeCodes: ["en","ru"],
  additionalMessages: [],
}

export const localeMessages = {
  'en_EN.json': () => import('../..\\locales\\en_EN.json' /* webpackChunkName: "lang-en_EN.json" */),
  'ru_RU.json': () => import('../..\\locales\\ru_RU.json' /* webpackChunkName: "lang-ru_RU.json" */),
}
