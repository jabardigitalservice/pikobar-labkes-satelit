<template>
    <div class="wrapper wrapper-content">
        <portal to="title-name">
          Tambah Lab Satelit
        </portal>
        <portal to="title-action">
          <div class="title-action">
            <nuxt-link to="/lab-satelit" class="btn btn-primary">List Lab Satelit</nuxt-link>
          </div>
        </portal>
        <div class="row">
            <div class="col-lg-8">
                <Ibox title="Form Tambah Lab Satelit">
                    <form @submit.prevent="submitForm" @keydown="form.onKeydown($event)">
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
        data: () => ({
            roles: [],
            errors: [],
            form: new Form({
                nama: '',
                alamat: '',
                latitude: '',
                longitude: '',
            }),
        }),

        created() {
            this.getRoles();
        },

        methods: {
            async getRoles() {
                let resp = await axios.get('/roles-option')
                this.roles = resp.data
            },

            async submitForm() {
                // Tambah Lab Satelit
                this.form.post('/lab-satelit')
                    .then(({
                        data
                    }) => {
                        console.log('response : ', data);
                        this.$swal.fire(
                            'Berhasil Tambah Data',
                            'Data Lab Satelit Berhasil ditambahkan',
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