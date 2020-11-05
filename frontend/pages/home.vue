<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Dashboard</portal>

    <!-- Web Satelit -->
    <tracking v-if="checkPermission('satelit')" />
    <pasien-diperiksa v-if="checkPermission('satelit')" />
    <charts v-if="checkPermission('satelit')" />
    <instansi-pengirim v-if="checkPermission('satelit')" />

    <!-- Dashboard superadmin -->
    <tracking-admin v-if="checkPermission('superadmin')" />
    <chart-admin v-if="checkPermission('superadmin')" />

  </div>
</template>

<script>
  import {
    mapGetters
  } from "vuex";
  import Tracking from './dashboard/tracking'
  import PasienDiperiksa from './dashboard/pasien-diperiksa'
  import Charts from './dashboard/charts'
  import InstansiPengirim from './dashboard/instansi-pengirim'
  import TrackingAdmin from './dashboard-admin/tracking'
  import ChartAdmin from './dashboard-admin/chart/charts'

  export default {
    middleware: "auth",
    components: {
      PasienDiperiksa,
      Tracking,
      Charts,
      InstansiPengirim,
      TrackingAdmin,
      ChartAdmin
    },

    computed: mapGetters({
      user: "auth/user"
    }),

    data() {
      return {
        params: {
          nama: null
        }
      };
    },
    head() {
      return {
        title: this.$t("home")
      };
    },
    methods: {
      checkPermission(menu) {
        var allow_role_id
        switch (menu) {
          case 'satelit':
            allow_role_id = [8]
            break;
          case 'superadmin':
            allow_role_id = [1]
            break;
        }
        return allow_role_id.indexOf(this.user.role_id) > -1
      },
    }
  };
</script>