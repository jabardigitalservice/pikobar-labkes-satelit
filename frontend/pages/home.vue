<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Dashboard</portal>
    <portal to="title-action">
      <div class="title-action text-left text-sm-right">
        Selamat Datang di Aplikasi PIKOBAR LAB
        <br />
        <strong>{{ user.name }}</strong>
      </div>
    </portal>
    <div class="grid">
        <!-- <chart-bar /> -->
        <!-- <ChartDoughnut />
        <ChartLine /> -->
      </div>
    <tracking v-if="checkPermission('verifikasi')"></tracking>
    <data-positif-negatif />
    <charts />
    <data-registrasi />
    <data-pengambilan-sampel />
    <data-ekstraksi />
    <data-rrt-pcr />
    <data-verifikasi />
    <data-validasi />
    <ekstraksi v-if="checkPermission('ekstraksi')"></ekstraksi>
    <pcr v-if="checkPermission('pcr')"></pcr>
    <registrasi v-if="checkPermission('registrasi')"></registrasi>
    <verifikasi-validasi v-if="checkPermission('verifikasi')"></verifikasi-validasi>
  </div>
</template>
 
<script>
import { mapGetters } from "vuex";
import Ekstraksi from './dashboard/ekstraksi'
import Pcr from './dashboard/pcr'
import Tracking from './dashboard/tracking'
import Registrasi from './dashboard/registrasi'
import VerifikasiValidasi from './dashboard/verifikasi-validasi'
import DataPositifNegatif from './dashboard/data-positif-negatif'
import Charts from './dashboard/charts'
import DataRegistrasi from './dashboard/data-registrasi'
import DataPengambilanSampel from './dashboard/data-pengambilan-sampel'
import DataEkstraksi from './dashboard/data-ekstraksi'
import DataRrtPcr from './dashboard/data-rrt-pcr'
import DataVerifikasi from './dashboard/data-verifikasi'
import DataValidasi from './dashboard/data-validasi'

export default {
  middleware: "auth",
  components: {
    Ekstraksi,
    Pcr,
    Tracking,
    Registrasi,
    VerifikasiValidasi,
    DataPositifNegatif,
    Charts,
    DataRegistrasi,
    DataPengambilanSampel,
    DataEkstraksi,
    DataRrtPcr,
    DataVerifikasi,
    DataValidasi
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
