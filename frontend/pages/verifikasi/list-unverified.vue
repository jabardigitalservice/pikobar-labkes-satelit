<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Menunggu Verifikasi</portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Sampel Menunggu Verifikasi">
          <p class="sub-header">Berikut ini adalah daftar sampel dari hasil pemeriksaan</p>

          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Hasil Pemeriksaan</label>
                <dynamic-input :form="params1" field="kesimpulan_pemeriksaan" 
                  :options="[{id: 'positif',name: 'POSITIF'},{id: 'negatif',name: 'NEGATIF'},{id: 'sampel kurang',name: 'SAMPEL KURANG'}]"
                  :hasSemua="true">
                </dynamic-input>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Fasyankes</label>
                <dynamic-input :form="params1" field="fasyankes" 
                  :options="listFasyankes"
                  :hasSemua="true">
                </dynamic-input>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Tanggal Registrasi (Awal)</label>
                <date-picker
                  placeholder="Pilih Tanggal"
                  format="d MMMM yyyy"
                  input-class="form-control"
                  :monday-first="true"
                  v-model="params1.tanggal_registrasi_start"
                />
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Tanggal Registrasi (Akhir)</label>
                <date-picker
                  placeholder="Pilih Tanggal"
                  format="d MMMM yyyy"
                  input-class="form-control"
                  :monday-first="true"
                  v-model="params1.tanggal_registrasi_end"
                />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Kota Domisili</label>
                <dynamic-input :form="params1" field="kota_domisili" 
                  :options="listKota"
                  :hasSemua="true">
                </dynamic-input>
              </div>
            </div>
            <div class="col-md-9">
              <button id="btn-export" class="btn btn-primary pull-right mt-4" 
                @click="onExport('verifikasi')"
              >
                <i class="fa fa-file-excel-o"></i> Export Excel
              </button>
            </div>
            
          </div>
          
          <ajax-table
            url="/v1/verifikasi/list"
            :oid="'verifikasi'"
            :params="params1"
            :config="{
                    autoload: true,
                    has_number: true,
                    has_entry_page: true,
                    has_pagination: true,
                    has_action: true,
                    has_search_input: true,
                    custom_header: '',
                    default_sort: '',
                    custom_empty_page: true,
                    class: {
                        table: [],
                        wrapper: ['table-responsive'],
                    }
                    }"
            :rowtemplate="'tr-verifikasi'"
            :columns="{
                      nomor_register: 'Nomor Register',
                      pasien_nama : 'Nama Pasien',
                      nomor_sampel : 'Nomor Sampel',
                      parameter_lab: 'Parameter Lab',
                      kesimpulan_pemeriksaan: 'Kesimpulan Pemeriksaan',
                    }"
          ></ajax-table>
        </Ibox>
      </div>
    </div>
  </div>
</template>
 
<script>
import axios from "axios";

export default {
  middleware: "auth",
  data() {
    return {
      params1: {
        fasyankes: "",
        kota_domilisi: "",
        tanggal_registrasi_start: "",
        tanggal_registrasi_end: "",
        kesimpulan_pemeriksaan: ""
      }
    };
  },
  async asyncData({route, store}){
    let listKota = await axios.get("/v1/list-kota-jabar");
    let listFasyankes = await axios.get("v1/list-fasyankes-jabar");

    if (listKota.data) {
      listKota = listKota.data.map(function(kota, index){
         let newKota = kota;
         newKota.name = kota.nama; 
         
         return newKota;
      })
    }

    if (listFasyankes.data) {
      listFasyankes = listFasyankes.data.map(function(fasyan, index){
        let newFasyankes = fasyan;
        newFasyankes.name = fasyan.nama;

        return newFasyankes;
      })
    }

    return {
      listKota,
      listFasyankes
    }
  },
  head() {
    return { title: "Sampel Hasil Pemeriksaan" };
  },
  watch: {
    "params1.fasyankes": function(newVal, oldVal) {
      this.$bus.$emit("refresh-ajaxtable", "verifikasi");
    },
    "params1.kota_domisili": function(newVal, oldVal) {
      this.$bus.$emit("refresh-ajaxtable", "verifikasi");
    },
    "params1.tanggal_registrasi_start": function(newVal, oldVal) {
      this.$bus.$emit("refresh-ajaxtable", "verifikasi");
    },
    "params1.tanggal_registrasi_end": function(newVal, oldVal) {
      this.$bus.$emit("refresh-ajaxtable", "verifikasi");
    },
    "params1.kesimpulan_pemeriksaan": function(newVal, oldVal) {
      this.$bus.$emit("refresh-ajaxtable", "verifikasi");
    },
  },
  methods: {
    
  }
};
</script>
