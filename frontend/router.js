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
  { path: '/error-role', name: 'error-role', component: page('error-role.vue'), meta: {parentName: 'home'} },
  { path: '/sample', name: 'sample.index', component: page('sample/index.vue'), meta: {parentName: 'home'} },
  { path: '/sample/add', name: 'sample.add', component: page('sample/add.vue'), meta: {parentName: 'sample.index'} },
  { path: '/settings',
    component: page('settings/index.vue'),
    children: [
      { path: '', redirect: { name: 'settings.profile' } },
      { path: 'profile', name: 'settings.profile', component: page('settings/profile.vue'), meta: {parentName: 'home'} },
      { path: 'password', name: 'settings.password', component: page('settings/password.vue'), meta: {parentName: 'home'} }
    ]},
  { path: '/pengguna', name: 'pengguna.index', component: page('pengguna/index.vue'), meta: {parentName: 'home'} },
  { path: '/pengguna/tambah', name:'pengguna.tambah', component:page('pengguna/tambah.vue'), meta: {parentName: 'pengguna.index'} },
  { path: '/pengguna/update/:id', name:'pengguna.update', component:page('pengguna/update.vue'),  meta: {parentName: 'pengguna.index'} },
  { path: '/registrasi/mandiri', name: 'registrasi.mandiri', component: page('registrasi-mandiri/index.vue'), meta: {parentName: 'home'} },
  { path: '/registrasi/mandiri/tambah', name: 'registrasi.mandiri.tambah', component: page('registrasi-mandiri/tambah.vue'), meta: {parentName: 'registrasi.mandiri'} },
  { path: '/registrasi/mandiri/detail', name: 'registrasi.mandiri.detail', component: page('registrasi-mandiri/detail.vue'), meta: {parentName: 'registrasi.mandiri'} },
  
  { path: '/registrasi/rujukan', name:'registrasi.rujukan', component: page('registrasi-rujukan/index.vue'), meta: {parentName: 'home'} },

  { path: '/ekstraksi', name:'ekstraksi.index', component: page('ekstraksi/index.vue'), meta: {parentName: 'home'} },
  { path: '/ekstraksi/detail/:id', name:'ekstraksi.detail', component: page('ekstraksi/detail.vue'), meta: {parentName: 'ekstraksi.index'} },
  { path: '/ekstraksi/edit/:id', name:'ekstraksi.edit', component: page('ekstraksi/edit.vue'), meta: {parentName: 'ekstraksi.index'} },
  { path: '/ekstraksi/dikembalikan', name:'ekstraksi.dikembalikan', component: page('ekstraksi/dikembalikan.vue'), meta: {parentName: 'ekstraksi.index'} },
  { path: '/ekstraksi/penerimaan', name:'ekstraksi.penerimaan', component: page('ekstraksi/penerimaan.vue'), meta: {parentName: 'ekstraksi.index'} },
  { path: '/ekstraksi/terima', name:'ekstraksi.terima', component: page('ekstraksi/terima.vue'), meta: {parentName: 'ekstraksi.penerimaan'} },
  { path: '/ekstraksi/terkirim', name:'ekstraksi.terkirim', component: page('ekstraksi/terkirim.vue'), meta: {parentName: 'ekstraksi.index'} },
  { path: '/ekstraksi/invalid', name:'ekstraksi.invalid', component: page('ekstraksi/invalid.vue'), meta: {parentName: 'ekstraksi.index'} },
  { path: '/ekstraksi/kirim', name:'ekstraksi.kirim', component: page('ekstraksi/kirim.vue'), meta: {parentName: 'ekstraksi.index'} },
  { path: '/ekstraksi/kirim-ulang', name:'ekstraksi.kirim-ulang', component: page('ekstraksi/kirim-ulang.vue'), meta: {parentName: 'ekstraksi.dikembalikan'} },

  { path: '/pcr/list-rna', name:'pcr.list-rna', component: page('pcr/list-rna.vue'), meta: {parentName: 'home'} },
  { path: '/pcr/list-pcr', name:'pcr.list-pcr', component: page('pcr/list-pcr.vue'), meta: {parentName: 'home'} },
  { path: '/pcr/list-hasil-pemeriksaan', name:'pcr.list-hasil-pemeriksaan', component: page('pcr/list-hasil-pemeriksaan.vue'), meta: {parentName: 'home'} },
  { path: '/pcr/list-hasil-inkonklusif', name:'pcr.list-hasil-inkonklusif', component: page('pcr/list-hasil-inkonklusif.vue'), meta: {parentName: 'home'} },
  { path: '/pcr/terima', name:'pcr.terima', component: page('pcr/terima.vue'), meta: {parentName: 'pcr.list-rna'} },
  { path: '/pcr/detail/:id', name:'pcr.detail', component: page('pcr/detail.vue'), meta: {parentName: 'pcr.list-pcr'} },
  { path: '/pcr/invalid/:id', name:'pcr.invalid', component: page('pcr/invalid.vue'), meta: {parentName: 'pcr.list-pcr'} },
  { path: '/pcr/input/:id', name:'pcr.input', component: page('pcr/input.vue'), meta: {parentName: 'pcr.list-pcr'} },

  { path: '/pemeriksaansampel', name:'pemeriksaansample.index', component: page('pemeriksaan/index.vue'), meta: {parentName: 'home'}},
]

export function createRouter () {
  return new Router({
    routes,
    scrollBehavior,
    mode: 'history'
  })
}
