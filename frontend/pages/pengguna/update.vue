<template>
    <div class="wrapper wrapper-content">
        <portal to="title-name">
          Update Pengguna
        </portal>
        <portal to="title-action">
          <div class="title-action">
            <nuxt-link to="/pengguna" class="btn btn-primary">List Pengguna</nuxt-link>
          </div>
        </portal>
        <div class="row">
            <div class="col-lg-8">
                <Ibox title="Form Tambah Pengguna">
                    <form @submit.prevent="submitForm">
                        <!-- Name -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">Nama Pengguna</label>
                            <div class="col-md-7">
                                <input v-model="form.name" :class="{ 'is-invalid': errors.name!=null }" type="text"
                                    name="name" class="form-control">
                                <p class="text-danger" v-if="errors.name">{{errors.name[0]}}</p>
                            </div>
                        </div>

                        <!-- Username -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">Username</label>
                            <div class="col-md-7">
                                <input v-model="form.username" :class="{ 'is-invalid': errors.username!=null }"
                                    type="text" name="username" class="form-control">
                                <p class="text-danger" v-if="errors.username">{{errors.username[0]}}</p>
                            </div>
                        </div>


                        <!-- Email -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">{{ $t('email') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.email" :class="{ 'is-invalid': errors.email!=null }" type="email"
                                    name="email" class="form-control">
                                <p class="text-danger" v-if="errors.email">{{errors.email[0]}}</p>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">{{ $t('password') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.password" :class="{ 'is-invalid': errors.password!=null }"
                                    type="password" name="password" class="form-control">
                                <p class="text-danger" v-if="errors.password">{{errors.password[0]}}</p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">Ulangi {{ $t('password') }}</label>
                            <div class="col-md-7">
                                <input v-model="form.password_confirmation" :class="{ 'is-invalid': errors.password_confirmation !=null }"
                                    type="password" name="password_confirmation " class="form-control">
                            </div>
                        </div>

                        <!-- Roles -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">Role</label>
                            <div class="col-md-7">
                                <select class="form-control" v-model="form.role_id"
                                    :class="{ 'is-invalid': errors.role_id!=null }">
                                    <option :value="item.id" :key="item.id" v-for="item in roles">{{item.text}}
                                    </option>
                                </select>
                                <p class="text-danger" v-if="errors.role_id">{{errors.role_id[0]}}</p>
                            </div>
                        </div>

                        <div class="form-group row" v-if="form.role_id == 2">
                            <label class="col-md-3 col-form-label text-md-right">Sebagai Lab Satelit</label>
                            <div class="col-md-7">
                                <select class="form-control" v-model="form.lab_satelit_id"
                                    :class="{ 'is-invalid': errors.lab_satelit_id!=null }">
                                    <option :value="item.id" :key="item.id" v-for="item in lab_satelit">{{item.text}}
                                    </option>
                                </select>
                                <p class="text-danger" v-if="errors.lab_satelit_id">{{errors.lab_satelit_id[0]}}</p>
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
            ...mapGetters({
                validator: "options/validator",
                lab_pcr: "options/lab_pcr",
                lab_satelit: "options/lab_satelit",
            }),
            id_user() {
                return this.$route.params.id;
            },
        },
        async asyncData({store, route}) {
            var f1, f2, f3, f4
            if (!store.getters['options/lab_pcr'].length) {
                f1 = store.dispatch('options/fetchLabPCR')
            }
            console.log(store.getters['options/validator'])
            if (!store.getters['options/validator'].length) {
                f2 = store.dispatch('options/fetchValidator')
            }

            if (!store.getters['options/lab_satelit'].length) {
                await store.dispatch('options/fetchLabSatelit')
            }
            f3 = axios.get('/roles-option')
            f4 = axios.get('/pengguna/' + route.params.id)
            await f1
            await f2
            let data = (await f4).data.result
            return {
                roles: (await f3).data,
                form: new Form({
                    name: data.name,
                    email: data.email,
                    username: data.username,
                    role_id: data.role_id,
                    lab_pcr_id: data.lab_pcr_id,
                    lab_satelit_id: data.lab_satelit_id,
                    validator_id: data.validator_id,
                    password: '',
                    password_confirmation: ''
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
                // Tambah User
                await axios.post('/pengguna/' + this.id_user, this.form)
                    .then((response) => {
                        console.log('response : ', response);
                        this.$swal.fire(
                            'Berhasil Update Data',
                            'Data Pengguna Berhasil dibah',
                            'success'
                        );
                        this.$router.push('/pengguna');
                    })
                    .catch((error) => {
                        console.log(error.response);
                        this.errors = error.response.data.error;
                    });
                // this.form.post('/pengguna/'+this.id_user)
                //     .then(({data})=>{
                //         console.log('response : ',data);
                //         this.$swal.fire(
                //             'Berhasil Update Data',
                //             'Data Pengguna Berhasil dibah',
                //             'success'
                //         );
                //         this.$router.push('/pengguna');
                //     })
                //     .catch((error) => {
                //         console.log(error.response);
                //         this.errors = error.response.data.error;
                //     });
            }
        }
    }
</script>