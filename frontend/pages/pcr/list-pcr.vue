<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">List Input Hasil</portal>
    <portal to="title-action">
      <div class="title-action">
        <router-link to="/pcr/import-excel-hasil" class="btn btn-import-export">
          <i class="fa fa-download" /> Import
        </router-link>
      </div>
    </portal>
    <div class="row">
      <div class="col-lg-12">
        <filter-input-hasil :oid="`pcr-analisis`" />
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <Ibox title="List Input Hasil">
          <ajax-table url="/v1/pcr/get-data" :oid="'pcr-analisis'" :params="params1" :disableSort="[]" :config="{
            autoload: true,
            has_number: true,
            has_entry_page: true,
            has_pagination: true,
            has_action: true,
            has_search_input: true,
            custom_header: '',
            default_sort: 'waktu_sample_taken',
            default_sort_dir:'desc',
            custom_empty_page: true,
            class: {
              table: [],
              wrapper: ['table-responsive'],
            }
          }" :rowtemplate="'tr-pcr-analisis'" :columns="{
            nomor_sampel : 'NO SAMPEL',
            nama_lengkap : 'NAMA',
            nik : 'NIK',
            instansi_pengirim : 'INSTANSI',
            waktu_sample_taken:'WAKTU DITERIMA',
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
  var debounce = require('lodash/debounce')

  export default {
    middleware: ['auth', 'checkrole'],
    meta: {
      allow_role_id: [8]
    },
    computed: mapGetters({
      user: "auth/user",
      lab_pcr: "options/lab_pcr",
    }),
    data() {
      return {
        params1: {
          nik: null,
          nomor_sampel: null,
          start_date: null,
          end_date: null,
          instansi_pengirim: null,
          kota: null,
        }
      };
    },
    async asyncData({
      store
    }) {
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
      'params1.lab_pcr_id': function (newVal, oldVal) {
        this.$bus.$emit('refresh-ajaxtable', 'pcr-analisis')
      },
    },
    head() {
      return {
        title: "List PCR"
      };
    }
  };
</script>