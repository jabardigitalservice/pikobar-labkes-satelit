<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">List Hasil Pemeriksaan PCR</portal>
    <portal to="title-action">
      <div class="title-action">
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="List Hasil Pemeriksaan PCR">
          <p
            class="sub-header"
          >Berikut ini adalah daftar analisa RT-PCR terhadap sampel</p>
          <div class="row" v-if="!user.lab_pcr_id">
            <div class="col-md-6">
              <div class="form-group">
                <label>Filter Laboratorium PCR</label>
                <select class="form-control" v-model="params1.lab_pcr_id">
                  <option value="">Semua Laboratorium</option>
                  <option :value="item.id" :key="item.id" v-for="item in lab_pcr">{{item.text}}</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group" v-if="params1.lab_pcr_id =='999999'">
                <label>Nama Lab</label>
                <input class="form-control" type="text" v-model="params1.lab_pcr_nama" @keyup="refreshDebounce"/>
              </div>
            </div>
          </div>
          <ajax-table
            url="/v1/pcr/get-data"
            :oid="'pcr-hasil-pemeriksaan'"
            :params="params1"
            :config="{
                    autoload: true,
                    has_number: true,
                    has_entry_page: true,
                    has_pagination: true,
                    has_action: true,
                    has_search_input: true,
                    custom_header: '',
                    default_sort: 'waktu_pcr_sample_analyzed',
                    default_sort_dir: 'desc',
                    custom_empty_page: true,
                    class: {
                        table: [],
                        wrapper: ['table-responsive'],
                    }
                    }"
            :rowtemplate="'tr-pcr-hasil-pemeriksaan'"
            :columns="{
                      nomor_register: 'Nomor Register',
                      nomor_sampel : 'Nomor Sampel',
                      kesimpulan_pemeriksaan : 'Hasil Pemeriksaan',
                      waktu_pcr_sample_analyzed:'Waktu Input',
                      sampel_status : 'Status',
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
        sampel_status: "analyzed",
        filter_inconclusive: false,
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
      this.$bus.$emit('refresh-ajaxtable', 'pcr-hasil-pemeriksaan')
    }, 500),
  },
  watch: {
    'params1.lab_pcr_id': function(newVal, oldVal) {
      this.$bus.$emit('refresh-ajaxtable', 'pcr-hasil-pemeriksaan')
    },
  },
  head() {
    return { title: "List Hasil Pemeriksaan PCR" };
  }
};
</script>
