<template>
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox">
        <div class="ibox-title">
          <div class="ibox-tools">
            <span class="label label-primary float-right">Total</span>
          </div>
          <h5>Data Verifikasi & Validasi</h5>
        </div>
        <div class="ibox-content">
          <div class="row">
            <div class="col-md-3">
              <h1 class="no-margins" v-if="!loading">{{ unverifyCounter | formatCurrency}}</h1>
              <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
              <div class="font-bold text-success">
                <small>Jumlah Belum Verifikasi</small>
                <!-- <router-link to="/ekstraksi/penerimaan" tag="span">
                  <i class="fa fa-level-up"></i>
                </router-link> -->
              </div>
            </div>
            <div class="col-md-3">
              <h1
                class="no-margins"
                v-if="!loading"
              >{{ verifiedCounter | formatCurrency}}</h1>
              <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
              <div class="font-bold text-warning">
                <small>Jumlah Terverifikasi</small>
                <!-- <router-link to="/ekstraksi" tag="span">
                  <i class="fa fa-level-up"></i>
                </router-link> -->
              </div>
            </div>
            <div class="col-md-3">
              <h1
                class="no-margins"
                v-if="!loading"
              >{{ unvalidateCounter | formatCurrency}}</h1>
              <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
              <div class="font-bold text-danger">
                <small>Jumlah Belum Validasi</small>
                <!-- <router-link to="/ekstraksi/dikembalikan" tag="span">
                  <i class="fa fa-level-up"></i>
                </router-link> -->
              </div>
            </div>
            <div class="col-md-3">
              <h1 class="no-margins" v-if="!loading">{{ validatedCounter | formatCurrency}}</h1>
              <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
              <div class="font-bold text-navy">
                <small>Jumlah Tervalidasi</small>
                <!-- <router-link to="/ekstraksi/terkirim" tag="span">
                  <i class="fa fa-level-up"></i>
                </router-link> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="col-lg-6">
       <Ibox title="Belum Verifikasi">
          <!-- <template v-slot:tools>
              <nuxt-link tag="button" to="/registrasi/mandiri/export-excel" class="btn btn-xs btn-success"><i class="fa fa-dowload"></i> Export Data</nuxt-link>
          </template> -->
          <ajax-table 
            url="/v1/verifikasi/list" 
            :oid="'verifikasi'"
            :params="params"
            :config="{
              autoload: true,
              has_number: true,
              has_entry_page: true,
              has_pagination: true,
              has_action: false,
              has_search_input: true,
              custom_header: '',
              default_sort: 'name',
              custom_empty_page: true,
              class: {
                  table: [],
                  wrapper: ['table-responsive'],
              }
              }"
              :rowtemplate="'tr-verifikasi-mini'"
              :columns="{
                nomor_register: 'Nomor Registrasi',
                nama_pasien: 'Pasien',
                action: ''
              }"></ajax-table>
        </Ibox>
    </div>

    <div class="col-lg-6">
       <Ibox title="Terverifikasi">
          <template v-slot:tools>
              <nuxt-link tag="button" to="/verifikasi/export-excel" class="btn btn-xs btn-success"><i class="fa fa-dowload"></i> Export Data</nuxt-link>
          </template>
          <ajax-table 
            url="/v1/verifikasi/list-verified" 
            :oid="'verified'"
            :params="params"
            :config="{
              autoload: true,
              has_number: true,
              has_entry_page: true,
              has_pagination: true,
              has_action: false,
              has_search_input: true,
              custom_header: '',
              default_sort: 'name',
              custom_empty_page: true,
              class: {
                  table: [],
                  wrapper: ['table-responsive'],
              }
              }"
              :rowtemplate="'tr-verified-mini'"
              :columns="{
                no_sampel:'Sampel',
                nama_pasien: 'Pasien',
                tgl_input:'Tanggal Input',
                action: ''
              }"></ajax-table>
        </Ibox>
    </div>

    <div class="col-lg-6">
       <Ibox title="Belum Validasi">
          <!-- <template v-slot:tools>
              <nuxt-link tag="button" to="/registrasi/mandiri/export-excel" class="btn btn-xs btn-success"><i class="fa fa-dowload"></i> Export Data</nuxt-link>
          </template> -->
          <ajax-table 
            url="/v1/validasi/list" 
            :oid="'validasi'"
            :params="params"
            :config="{
              autoload: true,
              has_number: true,
              has_entry_page: true,
              has_pagination: true,
              has_action: false,
              has_search_input: true,
              custom_header: '',
              default_sort: 'name',
              custom_empty_page: true,
              class: {
                  table: [],
                  wrapper: ['table-responsive'],
              }
              }"
              :rowtemplate="'tr-validasi-mini'"
              :columns="{
                nomor_sampel : 'Nomor Sampel',
                pasien_nama : 'Nama Pasien',
                action: ''
              }"></ajax-table>
        </Ibox>
    </div>

    <div class="col-lg-6">
       <Ibox title="Tervalidasi">
          <template v-slot:tools>
              <nuxt-link tag="button" to="/validasi/export-excel" class="btn btn-xs btn-success"><i class="fa fa-dowload"></i> Export Data</nuxt-link>
          </template>
          <ajax-table 
            url="/v1/validasi/list-validated" 
            :oid="'validated'"
            :params="params"
            :config="{
              autoload: true,
              has_number: true,
              has_entry_page: true,
              has_pagination: true,
              has_action: false,
              has_search_input: true,
              custom_header: '',
              default_sort: 'name',
              custom_empty_page: true,
              class: {
                  table: [],
                  wrapper: ['table-responsive'],
              }
              }"
              :rowtemplate="'tr-validated-mini'"
              :columns="{
                nomor_sampel : 'Nomor Sampel',
                pasien_nama : 'Nama Pasien',
                tanggal_validasi : 'Tanggal Validasi',
                action: ''
              }"></ajax-table>
        </Ibox>
    </div>

  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "ekstraksi",
  data() {
    return {
      loading: true,
      unverifyCounter: 0,
      verifiedCounter: 0,
      unvalidateCounter: 0,
      validatedCounter: 0,
    };
  },
  methods: {
    async loadData() {
      this.loading = true;
      try {

        let respUnverify = await axios.get("/v1/dashboard/counter-belum-verifikasi");
        this.unverifyCounter = respUnverify.data.data;

        let respVerified = await axios.get("/v1/dashboard/counter-terverifikasi");
        this.verifiedCounter = respVerified.data.data;

        let respUnvalidate = await axios.get("/v1/dashboard/counter-belum-validasi");
        this.unvalidateCounter = respUnvalidate.data.data;

        let respValidated = await axios.get("/v1/dashboard/counter-tervalidasi");
        this.validatedCounter = respValidated.data.data;

      } catch (e) {
        this.unverifyCounter = '-';
        this.verifiedCounter = '-';
        this.unvalidateCounter = '-';
        this.validatedCounter = '-';
      }
      this.loading = false;
    }
  },
  created() {
    this.loadData();
  }
};
</script>

<style>
</style>