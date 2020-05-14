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
  { path: '/sample/add/:id?', name: 'sample.add', component: page('sample/add.vue'), meta: {parentName: 'sample.index'} },
  { path: '/sample/edit/:id', name: 'sample.edit', component: page('sample/edit.vue'), meta: {parentName: 'sample.index'} },
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
  { path: '/lab-satelit', name: 'lab-satelit.index', component: page('lab-satelit/index.vue'), meta: {parentName: 'home'} },
  { path: '/lab-satelit/tambah', name:'lab-satelit.tambah', component:page('lab-satelit/tambah.vue'), meta: {parentName: 'lab-satelit.index'} },
  { path: '/lab-satelit/update/:id', name:'lab-satelit.update', component:page('lab-satelit/update.vue'),  meta: {parentName: 'lab-satelit.index'} },
  { path: '/registrasi/mandiri', name: 'registrasi.mandiri', component: page('registrasi-mandiri/index.vue'), meta: {parentName: 'home'} },
  { path: '/registrasi/mandiri/tambah', name: 'registrasi.mandiri.tambah', component: page('registrasi-mandiri/tambah.vue'), meta: {parentName: 'registrasi.mandiri'} },
  { path: '/registrasi/mandiri/detail/:register_id/:pasien_id', name: 'registrasi.mandiri.detail', 
                    component: page('registrasi-mandiri/detail.vue'), 
                    meta: {parentName: 'registrasi.mandiri'} },
  { path: '/registrasi/mandiri/update/:register_id/:pasien_id', name:'registrasi.mandiri.update', meta: {parentName: 'registrasi.mandiri'}, component: page('registrasi-mandiri/update.vue')},
  { path: '/registrasi/mandiri/export-excel', name:'registrasi.mandiri.export-excel', component: page('registrasi-mandiri/export-excel.vue'), meta: {parentName: 'registrasi.mandiri'} },
  { path: '/registrasi/mandiri/import-excel', name:'registrasi.mandiri.import-excel', component: page('registrasi-mandiri/import-excel.vue'), meta: {parentName: 'registrasi.mandiri'} },
  
  { path: '/registrasi/rujukan', name:'registrasi.rujukan', component: page('registrasi-rujukan/index.vue'), meta: {parentName: 'home'} },
  { path: '/registrasi/rujukan/add/:nomor_sampel', name:'registrasi.rujukan.add', component: page('registrasi-rujukan/add.vue'), meta: {parentName: 'home'} },
  { path: '/registrasi/rujukan/update/:register_id/:pasien_id', name:'registrasi.rujukan.update', component: page('registrasi-rujukan/update.vue'), meta: {parentName: 'home'} },
  { path: '/registrasi/rujukan/detail/:register_id/:pasien_id', name:'registrasi.rujukan.detail', component: page('registrasi-rujukan/detail.vue'), meta: {parentName: 'home'} },
  { path: '/registrasi/rujukan/import-excel', name:'registrasi.rujukan.import-excel', component: page('registrasi-rujukan/import-excel.vue'), meta: {parentName: 'registrasi.rujukan'} },
  { path: '/registrasi/rujukan/export-excel', name:'registrasi.rujukan.export-excel', component: page('registrasi-rujukan/export-excel.vue'), meta: {parentName: 'registrasi.mandiri'} },


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
  { path: '/pcr/terima/:nomor_sampels?', name:'pcr.terima', component: page('pcr/terima.vue'), meta: {parentName: 'pcr.list-rna'} },
  { path: '/pcr/detail/:id', name:'pcr.detail', component: page('pcr/detail.vue'), meta: {parentName: 'pcr.list-pcr'} },
  { path: '/pcr/invalid/:id', name:'pcr.invalid', component: page('pcr/invalid.vue'), meta: {parentName: 'pcr.list-pcr'} },
  { path: '/pcr/input/:id', name:'pcr.input', component: page('pcr/input.vue'), meta: {parentName: 'pcr.list-pcr'} },
  { path: '/pcr/import-excel-hasil', name:'pcr.import-excel-hasil', component: page('pcr/import-excel-hasil.vue'), meta: {parentName: 'pcr.list-pcr'} },

  { path: '/pemeriksaansampel', name:'pemeriksaansample.index', component: page('pemeriksaan/index.vue'), meta: {parentName: 'home'}},

  // Verifikasi
  { path: '/verifikasi/list-unverified', name:'verifikasi.index.unverified', component: page('verifikasi/list-unverified.vue'), meta: {parentName: 'home'} },
  { path: '/verifikasi/list-verified', name:'verifikasi.index.verified', component: page('verifikasi/list-verified.vue'), meta: {parentName: 'home'} },
  { path: '/verifikasi/detail/:id', name:'verifikasi.detail', component: page('verifikasi/detail.vue'), meta: {parentName: 'verifikasi.index.unverified'} },
  { path: '/verifikasi/edit/:id', name:'verifikasi.edit', component: page('verifikasi/edit.vue'), meta: {parentName: 'verifikasi.index.unverified'} },
  { path: '/verifikasi/export-excel', name:'verifikasi.export-excel', component: page('verifikasi/export-excel.vue'), meta: {parentName: 'verifikasi.index.verified'} },

  // Validasi
  { path: '/validasi/list-unvalidate', name:'validasi.index.unvalidate', component: page('validasi/list-unvalidate.vue'), meta: {parentName: 'home'} },
  { path: '/validasi/list-validated', name:'validasi.index.validated', component: page('validasi/list-validated.vue'), meta: {parentName: 'home'} },
  { path: '/validasi/detail/:id', name:'validasi.detail', component: page('validasi/detail.vue'), meta: {parentName: 'validasi.index.unvalidate'} },
  { path: '/validasi/edit/:id', name:'validasi.edit', component: page('validasi/edit.vue'), meta: {parentName: 'validasi.index.unvalidate'} },
  { path: '/validasi/export-excel', name:'validasi.export-excel', component: page('validasi/export-excel.vue'), meta: {parentName: 'validasi.index.validated'} },

  // Pelacakan Sampel
  { path: '/pelacakan-sampel', name:'pelacakan-sampel.index', component: page('pelacakan-sampel/index.vue'), meta: {parentName: 'home'} },
  { path: '/pelacakan-sampel/detail/:id', name:'pelacakan-sampel.detail', component: page('pelacakan-sampel/detail.vue'), meta: {parentName: 'pelacakan-sampel.index'} },

  
]

export function createRouter () {
  return new Router({
    routes,
    scrollBehavior,
    mode: 'history'
  })
}
