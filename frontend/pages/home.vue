<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Dashboard</portal>
    <portal to="title-action">
      <div class="title-action">
        Selamat Datang di Aplikasi PIKOBAR LAB
        <br />
        <strong>{{ user.name }}</strong>
      </div>
    </portal>
    <tracking v-if="checkPermission('verifikasi')"></tracking>
    <ekstraksi v-if="checkPermission('ekstraksi')"></ekstraksi>
    <pcr v-if="checkPermission('pcr')"></pcr>
    <registrasi v-if="checkPermission('registrasi')"></registrasi>
  </div>
</template>
 
<script>
import { mapGetters } from "vuex";
import Ekstraksi from './dashboard/ekstraksi'
import Pcr from './dashboard/pcr'
import Tracking from './dashboard/tracking'
import Registrasi from './dashboard/registrasi'

export default {
  middleware: "auth",
  components: {
    Ekstraksi,
    Pcr,
    Tracking,
    Registrasi
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
    return { title: this.$t("home") };
  },
  methods: {
    checkPermission(menu) {
      var allow_role_id
      switch (menu) {
        case 'registrasi':
          allow_role_id = [1, 6, 7, 2]
          break;
        case 'sample':
          allow_role_id = [1, 6, 7, 3]
          break;
        case 'ekstraksi':
          allow_role_id = [1, 6, 7, 4]
          break;
        case 'pcr':
          allow_role_id = [1, 6, 7, 5]
          break;
        case 'verifikasi':
          allow_role_id = [1, 6, 7]
          break;
        case 'validasi':
          allow_role_id = [1, 7]
          break;
        case 'master':
          allow_role_id = [1]
          break;
      }
      return allow_role_id.indexOf(this.user.role_id) > -1
    },
  }
};
</script>
