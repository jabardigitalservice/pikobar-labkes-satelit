<template>
  <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                <form role="search" class="navbar-form-custom" action="search_results.html">
                    <div class="form-group">
                        <input type="text" placeholder="Pelacakan Sampel..." class="form-control" name="top-search" id="top-search">
                    </div>
                </form>
            </div>
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <a @click.prevent="logout" href="#">
                        <i class="fa fa-sign-out"></i> {{ $t('logout') }}
                    </a>
                </li>
            </ul>

        </nav>
</template>
<script>
import { mapGetters } from 'vuex'


export default {
  components: {
  },

  data: () => ({
    appName: process.env.appName
  }),

  computed: mapGetters({
    user: 'auth/user'
  }),

  methods: {
    async logout () {
      // Log out the user.
      await this.$store.dispatch('auth/logout')

      this.$toast.success("Logout sukses", {
        icon: 'check',
        iconPack: 'fontawesome',
        duration: 5000
      })

      // Redirect to login.
      this.$router.push({ name: 'login' })
    }
  }
}

</script>