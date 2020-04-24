import Vue from 'vue'
import Router from 'vue-router'
import { scrollBehavior } from '~/utils'

Vue.use(Router)

const page = path => () => import(`~/pages/${path}`).then(m => m.default || m)

const routes = [
  { path: '/', redirect:'home' },

  { path: '/login', name: 'login', component: page('auth/login.vue') },
  { path: '/register', name: 'register', component: page('auth/register.vue') },
  { path: '/password/reset', name: 'password.request', component: page('auth/password/email.vue') },
  { path: '/password/reset/:token', name: 'password.reset', component: page('auth/password/reset.vue') },
  { path: '/email/verify/:id', name: 'verification.verify', component: page('auth/verification/verify.vue') },
  { path: '/email/resend', name: 'verification.resend', component: page('auth/verification/resend.vue') },

  { path: '/home', name: 'home', component: page('home.vue') },
  { path: '/sample', name: 'sample.index', component: page('sample/index.vue'), meta: {parentName: 'home'} },
  { path: '/sample/add', name: 'sample.add', component: page('sample/add.vue'), meta: {parentName: 'sample.index'} },
  { path: '/settings',
    component: page('settings/index.vue'),
    children: [
      { path: '', redirect: { name: 'settings.profile' } },
      { path: 'profile', name: 'settings.profile', component: page('settings/profile.vue') },
      { path: 'password', name: 'settings.password', component: page('settings/password.vue') }
    ]},
  { path: '/pengguna', name: 'pengguna.index', component: page('pengguna/index.vue') },
  { path: '/pengguna/tambah', name:'pengguna.tambah', component:page('pengguna/tambah.vue') },
  { path: '/pengguna/update/:id', name:'pengguna.update', component:page('pengguna/update.vue') },
  { path: '/registrasi/mandiri', name: 'registrasi.mandiri', component: page('registrasi-mandiri/index.vue') },
  { path: '/registrasi/mandiri/tambah', name: 'registrasi.mandiri.tambah', component: page('registrasi-mandiri/tambah.vue') },
  { path: '/registrasi/mandiri/detail', name: 'registrasi.mandiri.detail', component: page('registrasi-mandiri/detail.vue') },
  
  { path: '/registrasi/rujukan', name:'registrasi.rujukan', component: page('registrasi-rujukan/index.vue') },

  { path: '/ekstraksi', name:'ekstraksi.index', component: page('ekstraksi/index.vue') },
  { path: '/ekstraksi/pilih/:id', name:'ekstraksi.pilih', component: page('ekstraksi/pilih.vue') },
  { path: '/ekstraksi/pilihulang/:id', name:'ekstraksi.pilih', component: page('ekstraksi/pilihulang.vue') },
  { path: '/ekstraksi/detail/:id', name:'ekstraksi.detail', component: page('ekstraksi/detail.vue') },
  { path: '/ekstraksi/edit/:id', name:'ekstraksi.edit', component: page('ekstraksi/edit.vue') },
  { path: '/ekstraksi/dikembalikan', name:'ekstraksi.dikembalikan', component: page('ekstraksi/dikembalikan.vue') },

  { path: '/pemeriksaansampel', name:'pemeriksaansample.index', component: page('pemeriksaan/index.vue')},


]

export function createRouter () {
  return new Router({
    routes,
    scrollBehavior,
    mode: 'history'
  })
}
