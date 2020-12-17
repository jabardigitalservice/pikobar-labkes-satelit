import Vue from 'vue'
import Router from 'vue-router'
import { scrollBehavior } from '~/utils'

Vue.use(Router)

const page = path => () =>
    import (`~/pages/${path}`).then(m => m.default || m)

const routes = [
    { path: '/', redirect: 'home' },

    { path: '/login', name: 'login', component: page('auth/login.vue') },
    { path: '/home', name: 'home', component: page('home.vue') },
    { path: '/error-role', name: 'error-role', component: page('error-role.vue'), meta: { parentName: 'home' } },
    {
        path: '/settings',
        component: page('settings/index.vue'),
        children: [
            { path: '', redirect: { name: 'settings.profile' } },
            { path: 'profile', name: 'settings.profile', component: page('settings/profile.vue'), meta: { parentName: 'home' } },
            { path: 'password', name: 'settings.password', component: page('settings/password.vue'), meta: { parentName: 'home' } }
        ]
    },
    { path: '/registrasi/sampel', name: 'registrasi.sampel', component: page('registrasi-sampel/index.vue'), meta: { parentName: 'home' } },
    { path: '/registrasi/sampel/tambah', name: 'registrasi.index.tambah', component: page('registrasi-sampel/tambah.vue'), meta: { parentName: 'registrasi.sampel' } },
    { path: '/registrasi/sampel/terima', name: 'registrasi.index.terima', component: page('registrasi-sampel/terima-sampel.vue'), meta: { parentName: 'registrasi.sampel' } },
    {
        path: '/registrasi/sampel/detail/:register_id/:pasien_id',
        name: 'registrasi.index.detail',
        component: page('registrasi-sampel/detail.vue'),
        meta: { parentName: 'registrasi.sampel' }
    },
    { path: '/registrasi/sampel/update/:register_id/:pasien_id', name: 'registrasi.index.update', meta: { parentName: 'registrasi.sampel' }, component: page('registrasi-sampel/update.vue') },
    { path: '/registrasi/sampel/export-excel', name: 'registrasi.index.export-excel', component: page('registrasi-sampel/export-excel.vue'), meta: { parentName: 'registrasi.sampel' } },
    { path: '/registrasi/sampel/import-excel', name: 'registrasi.index.import-excel', component: page('registrasi-sampel/import-excel.vue'), meta: { parentName: 'registrasi.sampel' } },

    { path: '/input-hasil/list-input-hasil', name: 'pcr.list-pcr', component: page('pcr/list-pcr.vue'), meta: { parentName: 'home' } },
    { path: '/pcr/detail/:id', name: 'pcr.detail', component: page('pcr/detail.vue'), meta: { parentName: 'pcr.list-pcr' } },
    { path: '/pcr/invalid/:id', name: 'pcr.invalid', component: page('pcr/invalid.vue'), meta: { parentName: 'pcr.list-pcr' } },
    { path: '/pcr/input/:id', name: 'pcr.input', component: page('pcr/input.vue'), meta: { parentName: 'pcr.list-pcr' } },
    { path: '/pcr/import-excel-hasil', name: 'pcr.import input hasil', component: page('pcr/import-excel-hasil.vue'), meta: { parentName: 'pcr.list-pcr' } },

    { path: '/hasil-pemeriksaan', name: 'hasil-pemeriksaan.index.unverified', component: page('hasil-pemeriksaan/index.vue'), meta: { parentName: 'home' } },
    { path: '/hasil-pemeriksaan/detail/:id', name: 'hasil-pemeriksaan.index.detail', component: page('hasil-pemeriksaan/detail.vue'), meta: { parentName: 'hasil-pemeriksaan.index.unverified' } },
    { path: '/hasil-pemeriksaan/edit/:id', name: 'hasil-pemeriksaan.index.edit', component: page('hasil-pemeriksaan/edit.vue'), meta: { parentName: 'hasil-pemeriksaan.index.unverified' } },

    { path: '/user', name: 'user.index', component: page('user/index.vue'), meta: {parentName: 'home'}},
    { path: '/user/:id', name: 'user.show', component: page('user/show.vue'), meta: {parentName: 'user.index'}},
    { path: '/user/:id/edit', name: 'user.edit', component: page('user/edit.vue'), meta: {parentName: 'user.index'}},
    { path: '/registration/:token', name: 'user.registration', component: page('auth/register.vue'), meta: {parentName: 'registration.index'}},

    { path: '/dinkes', name: 'dinkes.index', component: page('dinkes/index.vue'), meta: {parentName: 'home'}},
    { path: '/pengguna/:id', name: 'dinkes.show', component: page('dinkes/show.vue'), meta: {parentName: 'dinkes.index'}},
    { path: '/pengguna/:id/edit', name: 'dinkes.edit', component: page('dinkes/edit.vue'), meta: {parentName: 'dinkes.index'}},
    { path: '/registration-dinkes/:token', name: 'dinkes.registration', component: page('auth/register-dinkes.vue'), meta: {parentName: 'registration.index'}},
    
    { path: '/registrasi/perujuk', name: 'registrasi.perujuk', component: page('registrasi-perujuk/index.vue'), meta: { parentName: 'home' } },
    { path: '/registrasi/perujuk/tambah', name: 'registrasi.index2.tambah', component: page('registrasi-perujuk/tambah.vue'), meta: { parentName: 'registrasi.sampel' } },
    { path: '/registrasi/perujuk/detail/:id', name: 'registrasi.index2.detail', component: page('registrasi-perujuk/detail.vue'), meta: { parentName: 'registrasi.sampel' } },
    { path: '/registrasi/perujuk/update/:id', name: 'registrasi.index2.update', component: page('registrasi-perujuk/update.vue'), meta: { parentName: 'registrasi.sampel' } },
    
    { path: '/hasil-pemeriksaan-perujuk', name: 'hasil-pemeriksaan-perujuk.index.unverified', component: page('hasil-pemeriksaan-perujuk/index.vue'), meta: { parentName: 'home' } },
    { path: '/hasil-pemeriksaan-perujuk/detail/:id', name: 'hasil-pemeriksaan-perujuk.index.detail', component: page('hasil-pemeriksaan-perujuk/detail.vue'), meta: { parentName: 'hasil-pemeriksaan-perujuk.index.unverified' } },
        
]

export function createRouter() {
    return new Router({
        routes,
        scrollBehavior,
        mode: 'history'
    })
}