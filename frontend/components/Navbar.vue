<template>
  <div class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
      <div class="navbar-minimalize minimalize-styl-2 btn btn-lg">
        <i class="fa fa-bars fa-lg"></i>
      </div>
    </div>
    <ul class="nav navbar-top-links navbar-right">
      <li class="dropdown">
        <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#" aria-expanded="false"
          style="padding-top: 1.5em">
          <i class="fa fa-bell-o fa-lg"></i>
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
        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
          <span class="block m-t-xs font-bold">{{user.name}}</span>
          <span class="text-blue text-xs block">
            {{user.email}}
            <b class="caret"></b>
          </span>
        </a>
        <ul class="dropdown-menu animated fadeInRight m-t-xs">
          <li>
            <router-link :to="{name: 'settings.profile'}" class="dropdown-item">Profile</router-link>
          </li>
          <li class="dropdown-divider"></li>
          <li>
            <a class="dropdown-item" href="#" @click.prevent="logout()">Logout</a>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</template>

<script>
  import {
    mapGetters
  } from 'vuex'
  import axios from "axios";

  export default {
    components: {},

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
      async logout() {
        // Log out the user.
        await this.$store.dispatch('auth/logout')

        this.$toast.success("Logout sukses", {
          icon: 'check',
          iconPack: 'fontawesome',
          duration: 5000
        })

        // Redirect to login.
        this.$router.push({
          name: 'login'
        })
      }
    },
    created() {
      // this.fetchNotifications()
      // setInterval(this.fetchNotifications, 60000)
    },
  }
</script>