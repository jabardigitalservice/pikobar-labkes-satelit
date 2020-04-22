<template>
    <div>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-sm-4">
                <h2>Tambah Pengguna</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <nuxt-link to="/home">Dashboard</nuxt-link>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong>Tambah Pengguna</strong>
                    </li>
                </ol>
            </div>
            <div class="col-sm-8">
                <div class="title-action">
                    <a href="" class="btn btn-primary">Daftar List Pengguna</a>
                </div>
            </div>
        </div>
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-8">
                    <Ibox title="Form Tambah Pengguna">
                        <form @submit.prevent="submitForm" @keydown="form.onKeydown($event)">
                            <!-- Name -->
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-md-right">Nama Pengguna</label>
                                <div class="col-md-7">
                                    <input v-model="form.name" :class="{ 'is-invalid': errors.name!=null }"
                                        type="text" name="name" class="form-control">
                                    <p class="text-danger" v-if="errors.name">{{errors.name[0]}}</p>
                                </div>
                            </div>

                            <!-- Username -->
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-md-right">Username</label>
                                <div class="col-md-7">
                                    <input v-model="form.username"
                                        :class="{ 'is-invalid': errors.username!=null }" type="text"
                                        name="username" class="form-control">
                                    <p class="text-danger" v-if="errors.username">{{errors.username[0]}}</p>
                                </div>
                            </div>


                            <!-- Email -->
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-md-right">{{ $t('email') }}</label>
                                <div class="col-md-7">
                                    <input v-model="form.email" :class="{ 'is-invalid': errors.email!=null }"
                                        type="email" name="email" class="form-control">
                                    <p class="text-danger" v-if="errors.email">{{errors.email[0]}}</p>
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-md-right">{{ $t('password') }}</label>
                                <div class="col-md-7">
                                    <input v-model="form.password"
                                        :class="{ 'is-invalid': errors.password!=null }" type="password"
                                        name="password" class="form-control">
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
                                    Tambah
                                </v-button>
                                </div>
                            </div>


                        </form>
                    </Ibox>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import { Form, HasError, AlertError } from 'vform'
    // Vue.component(HasError.name, HasError)
    // Vue.component(AlertError.name, AlertError)
    import axios from 'axios'

    export default {
        middleware: 'auth',
        data: () => ({
            roles: [],
            errors:[],
            form: new Form({
                name: '',
                email: '',
                username: '',
                role_id: '',
                password: '',
                password_confirmation: ''
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

            async submitForm () {
                // Tambah User
                this.form.post('/pengguna')
                    .then(({data})=>{
                        console.log('response : ',data);
                        this.$swal.fire(
                            'Berhasil Tambah Data',
                            'Data Pengguna Berhasil ditambahkan',
                            'success'
                        );
                        this.$router.push('/pengguna');
                    })
                    .catch((error) => {
                        console.log(error.response);
                        this.errors = error.response.data.error;
                    });
            }
        }
    }
</script>