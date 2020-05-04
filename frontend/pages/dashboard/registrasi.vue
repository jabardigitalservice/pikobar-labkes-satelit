<template>
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox">
        <div class="ibox-title">
          <div class="ibox-tools">
            <span class="label label-primary float-right">Total</span>
          </div>
          <h5>Data Registrasi Mandiri</h5>
        </div>
        <div class="ibox-content">
          <div class="row">
            <div class="col-md-3">
              <h1 class="no-margins" v-if="!loading">{{ data.total_registrasi | formatCurrency}}</h1>
              <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
              <div class="font-bold text-success">
                <small>Jumlah Pasien (RM & RR)</small>
                <router-link to="/ekstraksi/penerimaan" tag="span">
                  <i class="fa fa-level-up"></i>
                </router-link>
              </div>
            </div>
            <div class="col-md-3">
              <h1
                class="no-margins"
                v-if="!loading"
              >{{ data.registrasi_mandiri | formatCurrency}}</h1>
              <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
              <div class="font-bold text-warning">
                <small>Jumlah Pasien Mandiri</small>
                <router-link to="/ekstraksi" tag="span">
                  <i class="fa fa-level-up"></i>
                </router-link>
              </div>
            </div>
            <div class="col-md-3">
              <h1
                class="no-margins"
                v-if="!loading"
              >{{ data.belum_lengkap | formatCurrency}}</h1>
              <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
              <div class="font-bold text-danger">
                <small>Jumlah Belum Lengkap</small>
                <router-link to="/ekstraksi/dikembalikan" tag="span">
                  <i class="fa fa-level-up"></i>
                </router-link>
              </div>
            </div>
            <div class="col-md-3">
              <h1 class="no-margins" v-if="!loading">{{ data.done | formatCurrency}}</h1>
              <img
                v-if="loading"
                src="~/assets/css/plugins/blueimp/img/loading.gif"
                width="36"
                height="36"
              />
              <div class="font-bold text-navy">
                <small>Pemeriksaan Selesai</small>
                <router-link to="/ekstraksi/terkirim" tag="span">
                  <i class="fa fa-level-up"></i>
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="col-lg-12">
       <Ibox title="Registrasi Mandiri">
                <template v-slot:tools>
                    <nuxt-link tag="button" to="/registrasi/mandiri/import-excel" class="btn btn-xs btn-success"><i class="fa fa-upload"></i> Import Data</nuxt-link>
                    <nuxt-link tag="button" to="/registrasi/mandiri/export-excel" class="btn btn-xs btn-success"><i class="fa fa-dowload"></i> Export Data</nuxt-link>
                    <nuxt-link tag="button" to="/registrasi/mandiri/tambah" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Registrasi Baru</nuxt-link>
                </template>
                <ajax-table url="/registrasi-mandiri" :oid="'registrasi-mandiri'"
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
                    :rowtemplate="'tr-data-regis-mandiri'"
                    :columns="{
                      nomor_register: 'Nomor Registrasi',
                      nama_pasien: 'Pasien',
                      nama_kota: 'Domisili',
                      sumber_pasien: 'Sumber',
                      no_sampel:'Sampel',
                      tgl_input:'Tanggal Input'
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
      data:[]
    };
  },
  methods: {
    async loadData() {
      this.loading = true;
      try {
        let resp = await axios.get("/v1/dashboard/registrasi");
        this.data = resp.data;
      } catch (e) {
        this.data.total_registrasi = "-";
        this.data.registrasi_mandiri = "-";
        this.data.belum_lengkap = "-";
        this.data.done = "-";
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