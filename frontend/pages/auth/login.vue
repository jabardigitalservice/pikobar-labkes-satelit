<template>

  <div class="loginColumns animated fadeInDown">
    <div class="row" style="padding-top:60px;">

      <div class="col-md-6">
        <h2 class="font-bold">Welcome to Pikobar</h2>

        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sapiente voluptatem facilis nobis unde? Maiores,
          pariatur repudiandae! Excepturi vero, hic deserunt facilis voluptas, cum voluptate nisi alias vel, aut quasi.
          Maxime.</p>
        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Inventore ullam quo eius cum ducimus quae sunt
          voluptates enim, consectetur suscipit voluptatem nulla ipsa alias possimus laudantium quod quos, aut omnis?
        </p>
      
      </div>
      <div class="col-md-6">
        <div class="ibox-content">
          <form  class="m-t" @submit.prevent="login" @keydown="form.onKeydown($event)">
            <div class="form-group">
              <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" type="email" name="email"
                class="form-control" placeholder="Email">
              <has-error :form="form" field="email" />
              <!-- <input type="email" class="form-control" placeholder="Username" required=""> -->
            </div>
            <div class="form-group">
              <!-- <input type="password" class="form-control" placeholder="Password" required=""> -->
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
      <div class="col-md-6">
        Copyright Pikobar
      </div>
      <div class="col-md-6 text-right">
        <small>© 2020</small>
      </div>
    </div>
  </div>

</template>

<script>
  import Form from 'vform'

  export default {
    layout: 'login',
    head() {
      return {
        title: this.$t('login')
      }
    },

    data: () => ({
      form: new Form({
        email: '',
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
        await this.$store.dispatch('auth/fetchUser')

        // Redirect home.
        this.$router.push({
          name: 'home'
        })
      }
    }
  }
</script>