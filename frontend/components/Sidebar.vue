<template>
  <nav class="navbar-default navbar-static-side bg-white" role="navigation">
    <div class="sidebar-collapse" v-if="user">
      <ul class="nav metismenu" id="side-menu">
        <li class="nav-header">
          <div class="dropdown profile-element">
            <img alt="image" class="img-fluid" src="~/assets/img/logo-lab-black.png" />
          </div>
          <div class="logo-element">
            <img src="@/assets/img/logo-lab.png" style="width:25px" /></div>
        </li>
        <li v-if="checkPermission('dashboard')">
          <router-link to="/" tag="a">
            <i class="fa fa-home fa-fw" />
            <span class="nav-label">Dashboard</span>
          </router-link>
        </li>
        <li v-if="checkPermission('perujuk')">
          <router-link to="/registrasi/perujuk" tag="a">
            <i class="uil-user-square fa-fw" />
            <span class="nav-label">Registrasi Sampel</span>
          </router-link>
        </li>
        <li v-if="checkPermission('perujuk')">
          <router-link to="/hasil-pemeriksaan-perujuk" tag="a">
            <i class="uil-eye fa-fw" />
            <span class="nav-label">Hasil Pemeriksaan</span>
          </router-link>
        </li>
        <li v-if="checkPermission('admin')">
          <router-link to="/user" tag="a">
            <i class="uil-user-square fa-fw" />
            <span class="nav-label">User</span>
          </router-link>
        </li>
        <li v-if="checkPermission('satelit')">
          <router-link to="/registrasi/sampel" tag="a">
            <i class="uil-user-square fa-fw" />
            <span class="nav-label">Registrasi Sampel</span>
          </router-link>
        </li>
        <li v-if="checkPermission('satelit')">
          <router-link to="/input-hasil/list-input-hasil" tag="a">
            <i class="uil-atom fa-fw" />
            <span class="nav-label">Input Hasil</span>
          </router-link>
        </li>
        <li v-if="checkPermission('satelit')">
          <router-link to="/hasil-pemeriksaan/list-hasil-pemeriksaan" tag="a">
            <i class="uil-eye fa-fw" />
            <span class="nav-label">Hasil Pemeriksaan</span>
          </router-link>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script>
  import {
    mapGetters
  } from "vuex";
  import LocaleDropdown from "./LocaleDropdown";

  export default {
    components: {
      LocaleDropdown,
    },

    data: () => ({
      appName: process.env.appName,
    }),

    computed: mapGetters({
      user: "auth/user"
    }),

    methods: {
      checkPermission(menu) {
        let allow_role_id
        switch (menu) {
          case 'dashboard':
            allow_role_id = [1, 8]
            break;
          case 'satelit':
            allow_role_id = [8]
            break;
          case 'perujuk':
            allow_role_id = [9]
            break;
          case 'admin':
            allow_role_id = [1]
            break;
        }
        return allow_role_id.indexOf(this.user.role_id) > -1
      },
      async logout() {
        // Log out the user.
        await this.$store.dispatch("auth/logout");

        this.$toast.success("Logout sukses", {
          icon: 'check',
          iconPack: 'fontawesome',
          duration: 5000
        })

        // Redirect to login.
        this.$router.push({
          name: "login"
        });
      }
    },
  };
</script>

<style scoped>
  .profile-photo {
    width: 2rem;
    height: 2rem;
    margin: -0.375rem 0;
  }
</style>