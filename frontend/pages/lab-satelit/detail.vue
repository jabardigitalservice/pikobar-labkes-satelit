<template>
    <div class="wrapper wrapper-content">
        <portal to="title-name">
          Detail Lab Satelit
        </portal>
        <portal to="title-action">
          <div class="title-action">
            <nuxt-link to="/lab-satelit" class="btn btn-primary">List Lab Satelit</nuxt-link>
          </div>
        </portal>
        <div class="row">
            <div class="col-lg-8">
                <Ibox>
                <h3 class="header-title mt-2 mb-2">Informasi Lab Satelit</h3>
                <table class="table">
                  <tbody>
                    <tr>
                      <td width="40%"><b>Nama Lab Satelit</b></td>
                      <td width="60%">{{form.nama}}</td>
                    </tr>
                     <tr>
                      <td width="40%"><b>Alamat</b></td>
                      <td width="60%">{{ form.alamat }}</td>
                    </tr>
                     <tr>
                      <td width="40%"><b>Longitude</b></td>
                      <td width="60%">{{ form.longitude }}</td>
                    </tr>
                     <tr>
                      <td width="40%"><b>Latitude</b></td>
                      <td width="60%">{{ form.latitude }}
                          
                      </td>
                    </tr>
                  </tbody>
                </table>
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
            f4 = axios.get('/lab-satelit/' + route.params.id)
            await f1
            await f2
            let data = (await f4).data.result
            return {
                roles: (await f3).data,
                form: new Form({
                    nama: data.nama,
                    alamat: data.alamat,
                    longitude: data.longitude,
                    latitude: data.latitude,

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
                await axios.post('/lab-satelit/' + this.id_user, this.form)
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