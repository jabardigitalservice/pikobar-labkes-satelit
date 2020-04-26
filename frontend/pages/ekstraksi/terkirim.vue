<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Sampel yang Telah Dikirim</portal>
    <portal to="title-action"></portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Data Sampel yang telah dikirim">
          <p
            class="sub-header"
          >Berikut ini adalah daftar dari status registrasi yang sampelnya telah dipilih dan dikirim ke laboratorium tingkat 3</p>
          <div class="row">
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
            url="/v1/ekstraksi/get-data"
            :oid="'ekstraksi-kirim'"
            :params="params1"
            :config="{
                    autoload: true,
                    has_number: true,
                    has_entry_page: true,
                    has_pagination: true,
                    has_action: true,
                    has_search_input: true,
                    custom_header: '',
                    default_sort: 'waktu_extraction_sample_sent',
                    default_sort_dir: 'desc',
                    custom_empty_page: true,
                    class: {
                        table: [],
                        wrapper: ['table-responsive'],
                    }
                    }"
            :rowtemplate="'tr-ekstraksi-kirim'"
            :columns="{
                      nomor_register: 'Nomor Register',
                      nomor_sampel : 'Nomor Sampel',
                      jenis_sampel : 'Jenis Sampel',
                      lab_pcr_nama : 'Lab PCR',
                      waktu_extraction_sample_sent: 'Ekstraksi dikirim pada',
                    }"
          ></ajax-table>
        </Ibox>
      </div>
    </div>
  </div>
</template>
 
<script>
import axios from "axios";
var debounce = require('lodash/debounce')
export default {
  middleware: "auth",
  data() {
    return {
      params1: {
        lab_pcr_id: "",
        lab_pcr_nama: "",
        register_status: 'extraction_sent'
      }
    };
  },
  async asyncData() {
    let resp = await axios.get("/lab-pcr-option");
    let lab_pcr = resp.data;
    return {
      lab_pcr
    };
  },
  methods: {
    refreshDebounce: debounce(function () {
      this.$bus.$emit('refresh-ajaxtable', 'ekstraksi-kirim')
    }, 500),
  },
  watch: {
    'params1.lab_pcr_id': function(newVal, oldVal) {
      this.$bus.$emit('refresh-ajaxtable', 'ekstraksi-kirim')
    },
  },
  head() {
    return { title: "Sampel yang Telah Dikirim" };
  }
};
</script>
