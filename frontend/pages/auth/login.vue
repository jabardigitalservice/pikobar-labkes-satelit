<template>

  <div class="loginColumns animated fadeInDown">
    <div class="row">

      <div class="col-md-7">
        <div class="title-welcome">Selamat Datang di Website</div>
        <div class="title-welcome">Manajemen Laboratorium</div>
        <div class="title-welcome">COVID-19 Jawa Barat</div>

        <div class="title-footer row">
          <div class="col-md-12 row">
            <div class="col-md-6">
              <div class="row footer-logo-desc">Developed by</div>
              <div class="row">
                <img alt="image" class="img-footer-logo" src="~/assets/img/logo/pemprov-jabar.png" />
                <img alt="image" class="img-footer-logo" src="~/assets/img/logo/jds.png" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="row footer-logo-desc">Supported by</div>
              <div class="row">
                <img alt="image" class="img-footer-logo" src="~/assets/img/logo/itb.png" />
                <img alt="image" class="img-footer-logo" src="~/assets/img/logo/unpad.png" />
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-5">
        <div class="ibox-content" style="padding: 35px 30px 20px 30px;">
          <div class="col-md-12">
            <img alt="image" class="img-fluid" src="~/assets/img/logo-lab-black.png" />
            <div class="login-guide">Klik tombol log in untuk mengakses data</div>
          </div>

          <form class="m-t" @submit.prevent="login" @keydown="form.onKeydown($event)">
            <div class="form-group">
              <input v-model="form.username" :class="{ 'is-invalid': form.errors.has('username') }" type="text"
                name="username" class="form-control input-placeholder" placeholder="Username">
              <span class="fa fa-user errspan"></span>
              <has-error :form="form" field="username" />
            </div>

            <div class="form-group">
              <input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" type="password"
                name="password" class="form-control input-placeholder" placeholder="Password">
              <span class="fa fa-lock errspan"></span>
              <has-error :form="form" field="password" />
            </div>

            <v-button :loading="form.busy" class="btn btn-navy block full-width m-b">
              {{ $t('login').toUpperCase() }}
            </v-button>

          </form>
        </div>
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
          if (data.user.role_id == 1) {
            this.$swal.fire(
              'Login gagal',
              'Harap Login Sebagai Lab Satelit',
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
        } catch (e) {
          this.$swal.fire(
            'Login gagal',
            'Mohon cek username / password Anda',
            'error'
          )
          return
        }
      }
    }
  }
</script>