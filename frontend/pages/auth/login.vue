<template>

  <div class="loginColumns animated fadeInDown">
    <div class="row" style="padding-top:60px;">

      <div class="col-md-6">
        <img alt="image" class="img-fluid" src="~/assets/img/logo-lab-black.png" />
        <h2 class="font-bold">Manajemen Laboratorium COVID-19</h2>
      </div>
      <div class="col-md-6">
        <div class="ibox-content">
          <form  class="m-t" @submit.prevent="login" @keydown="form.onKeydown($event)">
            <div class="form-group">
              <input v-model="form.username" :class="{ 'is-invalid': form.errors.has('username') }" type="text" name="username"
                class="form-control" placeholder="Username">
              <has-error :form="form" field="username" />
            </div>
            <div class="form-group">
              <input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" type="password"
                name="password" class="form-control" placeholder="Password">
              <has-error :form="form" field="password" />
            </div>
            <v-button :loading="form.busy" class="btn btn-primary block full-width m-b">
              {{ $t('login') }}
            </v-button>

            <router-link :to="{ name: 'password.request' }" class="small ml-auto my-auto">
              {{ $t('forgot_password') }}
            </router-link>
          </form>
        </div>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col-md-8">
        Pemerintah Provinsi Jawa Barat | GTPP COVID-19 | PIKOBAR
      </div>
      <div class="col-md-4 text-right">
        <small>© 2020</small>
      </div>
    </div>
  </div>

</template>

<script>
  import Form from 'vform'

  export default {
    layout: 'login',
    middleware: 'guest',
    head() {
      return {
        title: this.$t('login')
      }
    },

    data: () => ({
      form: new Form({
        username: '',
        password: ''
      }),
      remember: false
    }),

    methods: {
      async login() {
        let data

        // Submit the form.
        try {
          const response = await this.form.post('/login')
          data = response.data
        } catch (e) {
          this.$swal.fire(
            'Login gagal',
            'Mohon cek username / password Anda',
            'error'
          )
          return
        }

        // Save the token.
        this.$store.dispatch('auth/saveToken', { 
          token: data.token,
          remember: this.remember
        })

        // Fetch the user.
        await this.$store.dispatch('auth/fetchUser', data.user)

        // Redirect home.
        this.$router.push({
          name: 'home'
        })
      }
    }
  }
</script>