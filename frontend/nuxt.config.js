require('dotenv').config()
const { join } = require('path')
const { copySync, removeSync } = require('fs-extra')
const webpack = require("webpack");

module.exports = {
  generate: {
    fallback: true
  },

  mode: 'spa', // Comment this for SSR

  srcDir: __dirname,

  env: {
    apiUrl: process.env.API_URL || process.env.APP_URL + '/api',
    appName: process.env.APP_NAME || 'Laravel Nuxt',
    appLocale: process.env.APP_LOCALE || 'en',
    githubAuth: !!process.env.GITHUB_CLIENT_ID,
    googleAuth: !!process.env.GOOGLE_CLIENT_ID,
    keycloakAuth: !!process.env.KEYCLOAK_CLIENTID
  },

  head: {
    title: process.env.APP_NAME,
    titleTemplate: '%s - ' + process.env.APP_NAME,
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: 'PIKOBAR Labs' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
    ],
    script: [
      { src: 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js' },
      { src:'https://cdn.jsdelivr.net/npm/jquery'},
      { src:'/metismenu.js'},
      // { src:'https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js'}
    ]
  },

  loading: { color: '#007bff' },

  router: {
    base: '/',
    middleware: ['locale', 'check-auth']
  },

  css: [
    { src: '~assets/sass/app.scss', lang: 'scss' },
    "~assets/font-awesome/css/font-awesome.css",
    "~assets/css/unicons.css",
    "~assets/css/animate.css",
    "~assets/css/style.css",
    "~assets/css/smart_wizard.min.css",
    "~assets/css/flatpickr.min.css",
  ],

  plugins: [
    '~components/global',
    '~plugins/i18n',
    '~plugins/vform',
    '~plugins/axios',
    // '~plugins/fontawesome',
    // "~plugins/bootstrap.js",
    "~plugins/event-bus",
    "~plugins/templates",
    '~plugins/nuxt-client-init', // Comment this for SSR
    // { src: '~assets/js/jquery-3.1.1.min.js', mode: 'client' },
    // { src: '~assets/js/bootstrap.min.js', mode: 'client' },
    // { src: '~assets/js/plugins/metisMenu/jquery.metisMenu.js', mode: 'client' },
    // { src: '~assets/js/popper.min.js', mode: 'client' },
    // { src:'https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js'},
    { src: '~assets/js/inspinia.js', mode: 'client' },
    { src: '~assets/js/jquery.smartWizard.min.js', mode: 'client' },
    { src: '~assets/js/flatpickr.min.js', mode: 'client' },
  ],

  modules: [
    'nuxt-sweetalert2',
    'portal-vue/nuxt',
    '@nuxtjs/toast',
    '@nuxtjs/router'
  ],

  build: {
    extractCSS: true,
    // vendor:["jquery", "bootstrap"],
    plugins: [
      // new webpack.ProvidePlugin({
      //   $: "jquery",
      //   Popper: ['popper.js', 'default']
      // })
    ],
    // vendor:['~assets/js/jquery-3.1.1.min.js',
    //         '~assets/js/bootstrap.min.js',
    //         '~assets/js/inspinia.js']
  },

  hooks: {
    generate: {
      done (generator) {
        if (generator.nuxt.options.dev === false && generator.nuxt.options.mode === 'spa') {
          const distDir = generator.nuxt.options.generate.dir;
          copySync(join(distDir, '404.html'), join(distDir, 'index.html'))
        }
      }
    }
  }
}
