<template>
  <div class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
      <div class="navbar-minimalize minimalize-styl-2 btn btn-lg">
        <i class="fa fa-bars fa-lg"></i>
      </div>
    </div>
    <ul class="nav navbar-top-links navbar-right">
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
            <router-link :to="{name: 'settings.profile'}" class="dropdown-item">
              <i class="fa fa-user fa-fw mr-1" />Profile
            </router-link>
          </li>
          <li class="dropdown-divider"></li>
          <li>
            <a class="dropdown-item text-red" href="#" @click.prevent="logout()">
              <i class="fa fa-home fa-fw mr-1" />Logout
            </a>
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