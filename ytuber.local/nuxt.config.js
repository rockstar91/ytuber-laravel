export default {
  ssr: true,
  env: {
    site: 'http://localhost',
	api_key: 'AIzaSyDJvmFWlG2jcJyCkPD8_wOR-sto7VHMsEA',
	hash: '6fa918f20226cb08aa609a4f425f6d85',
	recaptcha_site_key: '6LcAoxAiAAAAACX9WheQfY0q2HZsutJrx1kO7uIf',
    yoomoney: '4100117973372417',
  },
  server: {
    host: 'localhost', // default: localhost
    port: 3000,
  },
  publicRuntimeConfig: {
    recaptcha: {
      api_key: process.env.api_key || '6LcAoxAiAAAAAEROcGwV6x2yLTf_Mta-MFkMjWnb',
      siteKey: process.env.siteKey || '6LcAoxAiAAAAACX9WheQfY0q2HZsutJrx1kO7uIf'
    }
  },
  head: {
    title: 'ytuber',
    htmlAttrs: {
      lang: 'en'
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
      { name: 'format-detection', content: 'telephone=no' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
      { rel: 'stylesheet', href: 'https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900' },
    ],
    script: [
      {
        src: '/assets/js/main/jquery.min.js',
        type: 'text/javascript'
      },
      {
        src: '/assets/js/main/bootstrap.bundle.min.js',
        type: 'text/javascript'
      },
      {
        src: '/assets/js/plugins/loaders/blockui.min.js',
        type: 'text/javascript'
      },
      {
        src: '/assets/js/core/vue-youtube-embed.umd.js',
        type: 'text/javascript'
      },
      {
        src: '/assets/js/plugins/forms/styling/switchery.min.js',
        type: 'text/javascript'
      },
      {
        src: '/assets/js/plugins/ui/prism.min.js',
        type: 'text/javascript'
      },
      {
        src: '/assets/js/plugins/forms/styling/uniform.min.js',
        type: 'text/javascript'
      },
      {
        src: '/assets/js/application.js',
        type: 'text/javascript'
      },
/*      {
        src: 'vue-multiselect/dist/vue-multiselect.min.js"',
        type: 'text/javascript'
      },*/
      ]
  },

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: [
    '@/assets/css/icons/icomoon/styles.min.css',
    '@/assets/css/bootstrap.min.css',
    '@/assets/css/bootstrap_limitless.css',
    '@/assets/css/layout.min.css',
    '@/assets/css/components.min.css',
    '@/assets/css/colors.min.css',
  ],

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [
    {src: '~/plugins/pagination.js', mode: 'client'},
    {src: '~/plugins/multiselect.js', mode: 'client'},
    {src: '~/plugins/youtube.js', mode: 'client'},
    {src: '~/plugins/click-outside.js', mode: 'client'},
    {src: '~/plugins/chart.js', mode: 'client'},
    {src: './plugins/echo', mode: 'client'},
    {src: './plugins/emojiPicker.js', mode: 'client'},
    {src: './plugins/emoji-mart-vue-2.js', mode: 'client'},
    {src: './plugins/chat-scroll.js', mode: 'client'},
    {src: './plugins/anime.js', mode: 'client'},
    {src: './plugins/bcrypt.js', mode: 'client'}
    /*    {src: '~/plugins/vue-loading-overlay.js', mode: 'client'},*/
  ],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,
  loadingIndicator: {
    name: 'cube-grid',
    color: '#3B8070',
    background: 'red'
  },
  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
    '@nuxtjs/moment'
  ],
  axios: {
    baseUrl: 'http://localhost',
    credentials: true,
    init(axios) {
      axios.defaults.withCredentials = true
    }
  },

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    'bootstrap-vue/nuxt',
    '@nuxtjs/auth-next',
    '@nuxtjs/moment',
    '@nuxtjs/axios',
    ['nuxt-clipboard', { autoSetContainer: true }],
    '@nuxtjs/recaptcha',
    '@nuxtjs/toast',
    '@nuxtjs/i18n',
	'@nuxtjs/yandex-metrika',
    '@nuxtjs/google-analytics'
  ],
    yandexMetrika: {
    id: '90500704',
    webvisor: true,
  },
  googleAnalytics: {
    id: 'G-4DV9P9881R'
  },
  moment: {
    defaultLocale: 'ru',
    locales: ['ru']
  },
  i18n: {
    locales: [
      {
        code: 'en',
        file: 'en_EN.json',
        name: 'English'
      },
      {
        code: 'ru',
        file: 'ru_RU.json',
        name: 'Русский'
      }
    ],
    lazy: true,
    langDir: 'locales/',
    defaultLocale: 'ru'
  },
  recaptcha: {
    hideBadge: false, // Hide badge element (v3 & v2 via size=invisible)
    language: 'ru',   // Recaptcha language (v2)
    siteKey: process.env.siteKey,    // Site key for requests
    version: '3',     // Version
    //size: 'normal'        // Size: 'compact', 'normal', 'invisible' (v2)
  },
  router: {
    middleware: ['auth']
  },
  auth: {
    redirect: {
      login: '/login',
      logout: '/',
      home: '/dashboard'
    },
    cookie: {
      options: {
        expires: 90
      }
    },
    strategies: {
      'laravelSanctum':{
        provider: 'laravel/sanctum',
        url: 'http://localhost',
        endpoints:{
          login:{
            url:'/api/authlogin'
          }
        }
      }

    }
  },
  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {
    //extend(config,ctx){},
    //transpile: ['@stylelib']
    extend(config, {}) {
      config.node = {
        fs: 'empty'
      }
    }
  }
}
