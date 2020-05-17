<template>
    <div class="wrapper wrapper-content">
        <portal to="title-name">
          Tambah Hasil Lab
        </portal>
        <portal to="title-action">
          <div class="title-action">
            <nuxt-link to="/pcr/list-pcr" class="btn btn-primary">List PCR</nuxt-link>
          </div>
        </portal>
        <div class="row">
            <div class="col-lg-8">
                <Ibox title="Form Tambah Hasil Lab">
                    <form @submit.prevent="submitForm" @keydown="form.onKeydown($event)">
                        <!-- Name -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">NAMA KIT Pemeriksaan</label>
                            <div class="col-md-7">
                                <input placeholder="KIT Pemeriksaan" v-model="form.nama_kit_pemeriksaan" :class="{ 'is-invalid': errors.nama_kit_pemeriksaan!=null }" type="text"
                                    name="nama_kit_pemeriksaan" class="form-control" required>
                                <p class="text-danger" v-if="errors.nama_kit_pemeriksaan">{{errors.nama_kit_pemeriksaan[0]}}</p>
                            </div>
                        </div>

                        <!-- nomor sample -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">Nomor Sampel</label>
                            <div class="col-md-7">
                                <input v-model="form.nomor_sampel" :class="{ 'is-invalid': errors.nomor_sampel!=null }"
                                    type="text" name="nomor_sampel" class="form-control" placeholder="Nomor Sampel" required>
                                <p class="text-danger" v-if="errors.nomor_sampel">{{errors.nomor_sampel[0]}}</p>
                            </div>
                        </div>


                        <!-- RDRP -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">CT RDRP</label>
                            <div class="col-md-7">
                                <input v-model="form.ct_rdrp" :class="{ 'is-invalid': errors.ct_rdrp!=null }" type="text"
                                    name="ct_rdrp" class="form-control">
                                <p class="text-danger" v-if="errors.ct_rdrp">{{errors.ct_rdrp[0]}}</p>
                            </div>
                        </div>

                        <!-- CTN -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">CT N</label>
                            <div class="col-md-7">
                                <input v-model="form.ct_n" :class="{ 'is-invalid': errors.ct_n!=null }" type="text"
                                    name="ct_n" class="form-control">
                                <p class="text-danger" v-if="errors.ct_n">{{errors.ct_rdrp[0]}}</p>
                            </div>
                        </div>
                                                <!-- Email -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">CT E</label>
                            <div class="col-md-7">
                                <input v-model="form.ct_e" :class="{ 'is-invalid': errors.ct_e!=null }" type="text"
                                    name="ct_e" class="form-control">
                                <p class="text-danger" v-if="errors.ct_e">{{errors.ct_e[0]}}</p>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">CT IC</label>
                            <div class="col-md-7">
                                <input v-model="form.ct_ic" :class="{ 'is-invalid': errors.ct_ic!=null }"
                                    type="text" name="ct_ic" class="form-control" placeholder="CT IC">
                                <p class="text-danger" v-if="errors.ct_ic">{{errors.ct_ic[0]}}</p>
                            </div>
                        </div>



                        <!-- Roles -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">Kesimpulan</label>
                            <div class="col-md-7">
                                <select class="form-control" v-model="form.kesimpulan_pemeriksaan"
                                    :class="{ 'is-invalid': errors.kesimpulan_pemeriksaan!=null }">
                                    <option :value="item.id" :key="item.id" v-for="item in roles">{{item.text}}
                                    </option>
                                </select>
                                <p class="text-danger" v-if="errors.kesimpulan_pemeriksaan">{{errors.kesimpulan_pemeriksaan[0]}}</p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">Catatan Pemeriksaan</label>
                            <div class="col-md-7">
                                <input v-model="form.catatan_pemeriksaan" :class="{ 'is-invalid': errors.catatan_pemeriksaan !=null }"
                                    type="text" name="catatan_pemeriksaan" class="form-control" required placeholder="Catatan Pemeriksaan">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">Tanggal Periksa</label>
                            <div class="col-md-7">
                                <input v-model="form.tanggal_mulai_pemeriksaan" :class="{ 'is-invalid': errors.tanggal_mulai_pemeriksaan !=null }"
                                    type="date" name="tanggal_mulai_pemeriksaan" class="form-control" required placeholder="Tanggal Periksa">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-7 offset-md-3 d-flex">
                                <!-- Submit Button -->
                                <v-button :loading="form.busy">
                                    Tambah
                                </v-button>
                            </div>
                        </div>
                    </form>
                </Ibox>
            </div>
        </div>
    </div>
</template>
<script>
    import {
        Form,
        HasError,
        AlertError
    } from 'vform'
    // Vue.component(HasError.name, HasError)
    // Vue.component(AlertError.name, AlertError)
    import axios from 'axios'
    import { mapGetters } from "vuex";

    export default {
        middleware: ['auth', 'checkrole'],
        meta: {
            allow_role_id: [1]
        },
        computed: mapGetters({
            validator: "options/validator",
            lab_pcr: "options/lab_pcr",
            lab_satelit: "options/lab_satelit",
        }),
        async asyncData({store}) {
            if (!store.getters['options/lab_pcr'].length) {
                await store.dispatch('options/fetchLabPCR')
            }
            if (!store.getters['options/validator'].length) {
                await store.dispatch('options/fetchValidator')
            }
            if (!store.getters['options/lab_satelit'].length) {
                await store.dispatch('options/fetchLabSatelit')
            }
            return {}
        },
        data: () => ({
            roles: [],
            errors: [],
            form: new Form({
                nama_kit_pemeriksaan: '',
                nomor_sampel: '',
                kesimpulan_pemeriksaan: '',
                tanggal_mulai_pemeriksaan: '',
                ct_n: '',
                ct_e: '',
                ct_ic: '',
                ct_rdrp: '',
                catatan_pemeriksaan:''
            }),
        }),

        created() {
            this.getRoles();
        },

        methods: {
            async getRoles() {
                let resp = await axios.get('/roles-option')
                //this.roles = resp.data
                this.roles = [
                    {id:'positif','text':'Positif'},
                    {id:'negatif','text':'Negativ'},
                    {id:'inklusif','text':'Inklusif'}
                ]
            },

            async submitForm() {
                // Tambah User
                this.form.post('/pcr/add')
                    .then(({
                        data
                    }) => {
                        console.log('response : ', data);
                        this.$swal.fire(
                            'Berhasil Tambah Data',
                            'Data Pengguna Berhasil ditambahkan',
                            'success'
                        );
                        this.$router.push('/pcr/list-pcr');
                    })
                    .catch((error) => {
                        console.log(error.response);
                        this.errors = error.response.data.error;
                    });
            }
        }
    }
</script>