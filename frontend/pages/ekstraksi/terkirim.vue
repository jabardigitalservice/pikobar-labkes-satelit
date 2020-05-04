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
              <div class="form-group" v-if="params1.lab_pcr_id =='999999'">
                <label>Nama Lab</label>
                <input class="form-control" type="text" v-model="params1.lab_pcr_nama" @keyup="refreshDebounce"/>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Tanggal Pengiriman Ekstraksi</label>
                <date-picker
                  placeholder="Pilih Tanggal"
                  format="d MMMM yyyy"
                  input-class="form-control"
                  :monday-first="true"
                  v-model="params1.waktu_extraction_sample_sent"
                />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Hasil Pemeriksaan</label>
                <dynamic-input :form="params1" field="kesimpulan_pemeriksaan" 
                  :options="[{id: 'positif',name: 'POSITIF'},{id: 'negatif',name: 'NEGATIF'},{id: 'sampel kurang',name: 'SAMPEL KURANG'}]"
                  :hasSemua="true">
                </dynamic-input>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Status Pemeriksaan</label>
                <dynamic-input :form="params1" field="sampel_status" 
                  :options="[
                  {id: 'extraction_sent',name: 'Semua'},
                  {id: 'extraction_sample_sent',name: 'Telah Dikirim'},
                  {id: 'pcr_sample_received',name: 'Telah Diterima'},
                  {id: 'pcr_sample_analyzed',name: 'Telah Dianalisis'},
                  {id: 'sample_verified',name: 'Telah Diverifikasi'},
                  {id: 'sample_valid',name: 'Telah Divalidasi'}]"
                  :hasSemua="false">
                </dynamic-input>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Status Pemusnahan</label>
                <dynamic-input :form="params1" field="is_musnah_ekstraksi" 
                  :options="[{id: 'true',name: 'Sudah Dimusnahkan'},{id: 'false',name: 'Belum Dimusnahkan'}]"
                  :hasSemua="true">
                </dynamic-input>
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
                      sampel_status : 'Status',
                      kesimpulan_pemeriksaan : 'Hasil Pemeriksaan',
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
        sampel_status: 'extraction_sent',
        kesimpulan_pemeriksaan: "",
        is_musnah_ekstraksi: "",
        waktu_extraction_sample_sent: '',
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
      this.$bus.$emit('refresh-ajaxtable', 'ekstraksi-kirim')
    }, 500),
  },
  watch: {
    'params1.lab_pcr_id': function(newVal, oldVal) {
      this.$bus.$emit('refresh-ajaxtable', 'ekstraksi-kirim')
    },
    'params1.kesimpulan_pemeriksaan': function(newVal, oldVal) {
      this.$bus.$emit('refresh-ajaxtable', 'ekstraksi-kirim')
    },
    'params1.sampel_status': function(newVal, oldVal) {
      this.$bus.$emit('refresh-ajaxtable', 'ekstraksi-kirim')
    },
    'params1.is_musnah_ekstraksi': function(newVal, oldVal) {
      this.$bus.$emit('refresh-ajaxtable', 'ekstraksi-kirim')
    },
    'params1.waktu_extraction_sample_sent': function(newVal, oldVal) {
      this.$bus.$emit('refresh-ajaxtable', 'ekstraksi-kirim')
    },
  },
  head() {
    return { title: "Sampel yang Telah Dikirim" };
  }
};
</script>
