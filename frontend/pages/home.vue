<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Dashboard</portal>

    <tracking />
    <charts />
    <div class="row">
      <div class="col-md-12">
        <Ibox title="Instansi Pengirim">
          <ajax-table url="/v1/dashboard/instansi-pengirim" :disableSort="[]" :oid="'instansi_pengirim'" :config="{
            autoload: true,
            has_number: true,
            has_entry_page: false,
            has_pagination: true,
            has_action: false,
            has_search_input: false,
            custom_header: '',
            default_sort: '',
            custom_empty_page: true,
            class: {
              table: [],
              wrapper: ['table-responsive'],
            }
          }" :rowtemplate="'tr-instansi-pengirim'" :columns="{
            instansi_pengirim: 'INSTANSI PENGIRIM',
            y : 'TOTAL',
          }" />
        </Ibox>
      </div>
    </div>
  </div>
</template>

<script>
  import {
    mapGetters
  } from "vuex";
  import Tracking from './dashboard/tracking'
  import Charts from './dashboard/charts'

  export default {
    middleware: "auth",
    components: {
      Tracking,
      Charts
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