<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Penerimaan Sampel</portal>
    <portal to="title-action">
      <div class="title-action">
        <router-link to="/sample/add" class="btn btn-primary">
          <i class="fa fa-plus"></i> Sampel Baru
        </router-link>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Penerimaan atau Pengambilan Sampel">
          <p
            class="sub-header"
          >Scan / masukan nomor barcode salah satu sampel untuk register pasien rujukan atau pengambilan sampel pasien mandiri</p>
          <form id="scanbarcode row" @submit.prevent="scanBarcode()">
            <center>
              <div class="form-group">
                <input
                  id="barcodesampel"
                  v-model="input_nomor_sampel"
                  class="form-control col-md-3"
                  name
                  placeholder="Scan..."
                  type="text"
                  :readonly="loading"
                  tabindex="1"
                  required
                  autofocus
                />
                <br />
                <button type="submit" class="mt-2 btn btn-md btn-primary"
                  :disabled="loading"
                  :class="{'btn-loading': loading}"
                >Tambahkan Informasi Sampel</button>
              </div>
            </center>
          </form>
        </Ibox>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Sampel dari Register">
          <p
            class="sub-header">Berikut ini adalah daftar dari registrasi yang belum ada status penerimaan atau pengambilan sampel, Silahkan pilih dan lakukan Ambil atau Terima Sampel Pasien</p>
          <ajax-table
            url="/sample/get-data"
            :oid="'sample-register'"
            :params="params1"
            :config="{
                    autoload: true,
                    has_number: true,
                    has_entry_page: true,
                    has_pagination: true,
                    has_action: true,
                    has_search_input: true,
                    custom_header: '',
                    default_sort: 'waktu_waiting_sample',
                    custom_empty_page: true,
                    class: {
                        table: [],
                        wrapper: ['table-responsive'],
                    }
                    }"
            :rowtemplate="'tr-sample-register'"
            :columns="{
                      nomor_sampel: 'Sampel',
                      nomor_register: 'Nomor Registrasi',
                      jenis_sampel_nama : 'Jenis Sampel',
                    }"
          ></ajax-table>
        </Ibox>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Status yang telah dikirim">
          <p class="sub-header">Berikut adalah status yang telah dikirimkan ke Lab Ekstraksi</p>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Jenis Sampel</label>
                <dynamic-input :form="params2" field="jenis_sampel_nama" 
                  :options="jenis_sampel"
                  :hasSemua="true">
                </dynamic-input>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Tanggal Penerimaan Sampel</label>
                <date-picker
                  placeholder="Pilih Tanggal"
                  format="d MMMM yyyy"
                  input-class="form-control"
                  :monday-first="true"
                  v-model="params2.waktu_sample_taken"
                />
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Kondisi Sampel</label>
                    <dynamic-input :form="params2" field="petugas_pengambil" 
                      :options="['Baik','Sampel Sedikit','Tabung Rusak']" 
                      :has-semua="true"
                      placeholder="Masukkan kondisi sampel">
                    </dynamic-input>
              </div>
            </div>
          </div>
          <ajax-table
            url="/sample/get-data"
            :oid="'sample-sent'"
            :params="params2"
            :config="{
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
                    }"
            :rowtemplate="'tr-sample-sent'"
            :columns="{
                      nomor_sampel: 'Sampel',
                      nomor_register: 'Nomor Registrasi',
                      jenis_sampel_nama : 'Jenis Sampel',
                      waktu_sample_taken : 'Waktu Penerimaan Sampel',
                      petugas_pengambil : 'Kondisi Sampel',
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

export default {
  middleware: "auth",
  computed: mapGetters({
    jenis_sampel: "options/jenis_sampel",
  }),
  data() {
    return {
      params1: { 
        sampel_status: "waiting_sample"
      },
      params2: {
        sampel_status: "sample_sent",
        waktu_sample_taken: "",
        petugas_pengambil: "",
        jenis_sampel_nama: "",
      },
      input_nomor_sampel: '',
      loading: false,
    };
  },
  async asyncData({route, store}) {
    if (!store.getters['options/jenis_sampel'].length) {
      await store.dispatch('options/fetchJenisSampel')
    }
  },
  methods: {
    async scanBarcode() {
      let input_nomor_sampel = this.input_nomor_sampel
      this.loading = true
      let resp = (
        await axios.get("v1/sampel/cek-nomor-sampel", {
          params: {
            nomor_sampel: this.input_nomor_sampel,
          }
        })
      ).data;
      this.loading = false
      if (resp.valid) {
        this.$router.push('/sample/edit/' + input_nomor_sampel)
      } else {
        this.$router.push('/sample/add/' + input_nomor_sampel)
      }
    }
  },
  watch: {
    'params2.waktu_sample_taken': function(newVal, oldVal) {
      this.$bus.$emit('refresh-ajaxtable', 'sample-sent')
    },
    'params2.petugas_pengambil': function(newVal, oldVal) {
      this.$bus.$emit('refresh-ajaxtable', 'sample-sent')
    },
    'params2.jenis_sampel_nama': function(newVal, oldVal) {
      this.$bus.$emit('refresh-ajaxtable', 'sample-sent')
    },
  },
  head() {
    return { title: "Penerimaan Sampel" };
  }
};
</script>
