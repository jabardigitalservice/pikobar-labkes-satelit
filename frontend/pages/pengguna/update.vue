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

    export default {
        middleware: 'auth',
        data: () => ({
            roles: [],
            errors: [],
            form: new Form({
                name: '',
                email: '',
                username: '',
                role_id: '',
                password: '',
            }),
        }),

        computed: {
            id_user() {
                return this.$route.params.id;
            }
        },

        created() {
            this.getRoles();
            this.getData();
            console.log(this.id_user);
        },

        methods: {
            async getRoles() {
                let resp = await axios.get('/roles-option')
                this.roles = resp.data
            },

            async getData() {
                let resp = await axios.get('/pengguna/' + this.id_user)
                this.form = resp.data.result;
            },

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