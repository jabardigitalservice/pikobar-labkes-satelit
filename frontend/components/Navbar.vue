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
                <li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="fa fa-bell"></i>
                        <span class="label label-primary" v-if="notifications.count > 0">{{ notifications.count }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li v-for="(notification, $index) in notifications.data" :key="$index">
                            <nuxt-link tag="a" :to="notification.link" class="dropdown-item">
                                <div>
                                    <i class="fa-fw" :class="notification.icon"></i> {{ notification.message }}
                                </div>
                            </nuxt-link>
                        </li>
                        <li v-if="notifications.count == 0">
                            <a class="dropdown-item">
                                <div>
                                    <em>Tidak ada notifikasi</em>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
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
import axios from "axios";

export default {
  components: {
  },

  data: () => ({
    appName: process.env.appName,
    notifications: {
      data: [],
      count: 0,
    }
  }),

  computed: mapGetters({
    user: 'auth/user'
  }),

  methods: {
    async fetchNotifications() {
      let resp = await axios.get("/v1/dashboard/notifications");
      this.notifications.data = resp.data.data
      this.notifications.count = resp.data.count
    },
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
  },
  created() {
    this.fetchNotifications()
    setInterval(this.fetchNotifications, 60000)
  },
}

</script>