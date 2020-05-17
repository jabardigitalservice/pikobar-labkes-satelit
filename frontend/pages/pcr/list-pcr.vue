<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">List Input Hasil</portal>
    <portal to="title-action">
      <div class="title-action">
        <router-link to="/pcr/tambah" class="btn btn-primary">
          <i class="uil-plus"></i> Tambah
        </router-link>

        <router-link to="/pcr/import-excel-hasil" class="btn btn-primary">
          <i class="uil-file-download"></i> Import Input Hasil
        </router-link>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="List Input Hasil">
          <ajax-table
            url="/v1/pcr/get-data"
            :oid="'pcr-analisis'"
            :params="params1"
            :config="{
                    autoload: true,
                    has_number: true,
                    has_entry_page: true,
                    has_pagination: true,
                    has_action: true,
                    has_search_input: true,
                    custom_header: '',
                    default_sort: 'waktu_pcr_sample_received',
                    custom_empty_page: true,
                    class: {
                        table: [],
                        wrapper: ['table-responsive'],
                    }
                    }"
            :rowtemplate="'tr-pcr-analisis'"
            :columns="{
                      nomor_sampel : 'Nomor Sampel',
                      nama_lengkap : 'Nama',
                      nik : 'Nik Pasien',
                      instansi_pengirim : 'Instansi Pengirim',
                      waktu_pcr_sample_received:'Waktu Diterima',
                    }"
          ></ajax-table>
        </Ibox>
      </div>
    </div>
  </div>
</template>
 
<script>
import axios from "axios";
import { mapGetters } from "vuex";
var debounce = require('lodash/debounce')

export default {
  middleware: "auth",
  computed: mapGetters({
    user: "auth/user",
    lab_pcr: "options/lab_pcr",
  }),
  data() {
    return {
      params1: {
        lab_pcr_id: "",
        lab_pcr_nama: "",
        sampel_status: "pcr_sample_received"
      }
    };
  },
  async asyncData({store}) {
    if (!store.getters['options/lab_pcr'].length) {
      await store.dispatch('options/fetchLabPCR')
    }
    return {}
  },
  methods: {
    refreshDebounce: debounce(function () {
      this.$bus.$emit('refresh-ajaxtable', 'pcr-analisis')
    }, 500),
  },
  watch: {
    'params1.lab_pcr_id': function(newVal, oldVal) {
      this.$bus.$emit('refresh-ajaxtable', 'pcr-analisis')
    },
  },
  head() {
    return { title: "List PCR" };
  }
};
</script>
