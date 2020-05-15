<template>
    <div class="wrapper wrapper-content">
        <portal to="title-name">
          Update List Satelit
        </portal>
        <portal to="title-action">
          <div class="title-action">
            <nuxt-link to="/list-satelit" class="btn btn-primary">List List Satelit</nuxt-link>
          </div>
        </portal>
        <div class="row">
            <div class="col-lg-8">
                <Ibox title="Form Tambah Pengguna">
                    <form @submit.prevent="submitForm">
                        <!-- Nama -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">Nama</label>
                            <div class="col-md-7">
                                <input v-model="form.nama" :class="{ 'is-invalid': errors.nama!=null }" type="text"
                                    name="nama" class="form-control">
                                <p class="text-danger" v-if="errors.nama">{{errors.nama[0]}}</p>
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">Alamat</label>
                            <div class="col-md-7">
                                <textarea name="alamat" id="" cols="30" rows="5" class="form-control"
                                v-model="form.alamat" :class="{ 'is-invalid': errors.alamat!=null }"
                                ></textarea>
                                <p class="text-danger" v-if="errors.alamat">{{errors.alamat[0]}}</p>
                            </div>
                        </div>

                        <!-- Latitude -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">Latitude</label>
                            <div class="col-md-7">
                                <input v-model="form.latitude" :class="{ 'is-invalid': errors.latitude!=null }" type="text"
                                    name="latitude" class="form-control">
                                <p class="text-danger" v-if="errors.latitude">{{errors.latitude[0]}}</p>
                            </div>
                        </div>

                        <!-- Longitude -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">Longitude</label>
                            <div class="col-md-7">
                                <input v-model="form.longitude" :class="{ 'is-invalid': errors.longitude!=null }" type="text"
                                    name="longitude" class="form-control">
                                <p class="text-danger" v-if="errors.longitude">{{errors.longitude[0]}}</p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-7 offset-md-3 d-flex">
                                <!-- Submit Button -->
                                <v-button :loading="form.busy">
                                    Update
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
        computed: {
            id_lab() {
                return this.$route.params.id;
            },
        },
        async asyncData({store, route}) {
            var f3, f4
            f3 = axios.get('/roles-option')
            f4 = axios.get('/lab-satelit/' + route.params.id)
            let data = (await f4).data.result
            return {
                roles: (await f3).data,
                form: new Form({
                    nama: data.nama,
                    alamat: data.alamat,
                    latitude: data.latitude,
                    longitude: data.longitude,
                }),
            }
        },
        data: () => ({
            roles: [],
            errors: [],
        }),

        created() {
        },

        methods: {
            async submitForm() {
                // Tambah Lab Satelit
                this.form.post('/lab-satelit/' + this.id_lab)
                    .then(({
                        data
                    }) => {
                        console.log('response : ', data);
                        this.$swal.fire(
                            'Berhasil Update Data',
                            'Data Lab Satelit Berhasil diubah',
                            'success'
                        );
                        this.$router.push('/lab-satelit');
                    })
                    .catch((error) => {
                        console.log(error.response);
                        this.errors = error.response.data.error;
                    });
                
            }
        }
    }
</script>