<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Terverifikasi</portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Sampel Terverifikasi">
          <p class="sub-header">
            Berikut ini adalah daftar sampel dari hasil pemeriksaan
          </p>

          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Hasil Pemeriksaan</label>
                <dynamic-input :form="params1" field="kesimpulan_pemeriksaan" v-model="params1.kesimpulan_pemeriksaan"
                  :options="[{id: 'positif',name: 'POSITIF'},{id: 'negatif',name: 'NEGATIF'},{id: 'sampel kurang',name: 'SAMPEL KURANG'}]"
                  :hasSemua="true">
                </dynamic-input>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Fasyankes</label>
                <dynamic-input :form="params1" field="fasyankes" v-model="params1.fasyankes" 
                  :options="listFasyankes"
                  :hasSemua="true">
                </dynamic-input>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Tanggal Verifikasi (Awal)</label>
                <date-picker
                  placeholder="Pilih Tanggal"
                  format="d MMMM yyyy"
                  input-class="form-control"
                  :monday-first="true"
                  v-model="params1.tanggal_verifikasi_start"
                />
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Tanggal Verifikasi (Akhir)</label>
                <date-picker
                  placeholder="Pilih Tanggal"
                  format="d MMMM yyyy"
                  input-class="form-control"
                  :monday-first="true"
                  v-model="params1.tanggal_verifikasi_end"
                />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Kota Domisili</label>
                <dynamic-input :form="params1" field="kota_domisili" v-model="params1.kota_domilisi"
                  :options="listKota"
                  :hasSemua="true">
                </dynamic-input>
              </div>
            </div>
            <div class="col-md-9">
              <button id="btn-export" class="btn btn-primary pull-right mt-4" 
                @click="onExport('verified')"
              >
                <i class="fa fa-file-excel-o"></i> Export Excel
              </button>
            </div>
            
          </div>

          <ajax-table
            url="/v1/verifikasi/list-verified"
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
                :rowtemplate="'tr-verified'"
                :columns="{
                      nomor_register: 'Nomor Register',
                      pasien_nama : 'Nama Pasien',
                      kota_domilisi: 'Kota Domisili',
                      nomor_sampel : 'Nomor Sampel',
                      parameter_lab: 'Parameter Lab',
                      kondisi_sampel: 'Kondisi Sampel',
                      kesimpulan_pemeriksaan: 'Kesimpulan Pemeriksaan',
                      status_sampel: 'Status',
                      tanggal_verifikasi: 'Tanggal Diverifikasi'
                    }"
          ></ajax-table>
        </Ibox>
      </div>
    </div>

  </div>
</template>
 
<script>
import axios from "axios";
import Form from "vform";


export default {
  middleware: "auth",
  data() {
    return {
      params1: {
        fasyankes: "",
        kota_domisili: "",
        tanggal_verifikasi_start: "",
        tanggal_verifikasi_end: "",
        kesimpulan_pemeriksaan: "",
        kategori: ""
      },
    };
  },
  async asyncData({route, store}){
    let listKota = await axios.get("/v1/list-kota-jabar");
    let listFasyankes = await axios.get("v1/list-fasyankes-jabar");
    let listKategori = await axios.get("v1/verifikasi/list-kategori");


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

    if (listKategori.data.data) {
      listKategori = listKategori.data.data.map(function(kategori, index){
        let newKategori = kategori;
        newKategori.name = kategori.sumber_pasien;
        newKategori.id = kategori.sumber_pasien;

        return newKategori;
      })
    }


    return {
      listKota,
      listFasyankes,
      listKategori
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
    "params1.tanggal_verifikasi_start": function(newVal, oldVal) {
      this.$bus.$emit("refresh-ajaxtable", "verifikasi");
    },
    "params1.tanggal_verifikasi_end": function(newVal, oldVal) {
      this.$bus.$emit("refresh-ajaxtable", "verifikasi");
    },
    "params1.kesimpulan_pemeriksaan": function(newVal, oldVal) {
      this.$bus.$emit("refresh-ajaxtable", "verifikasi");
    },
    "params1.kategori": function(newVal, oldVal) {
      this.$bus.$emit("refresh-ajaxtable", "verifikasi");
    },
  },
  methods: {
    async onExport(type){
      try {
        this.loading = true;

        let form = new Form({...this.params1, type: type})

        axios({
                url: process.env.apiUrl + "/v1/verifikasi/export-excel",
                params: form,
                method: 'GET',
                responseType: 'blob',
            }).then((response) => {
                const blob = new Blob([response.data], {type: response.data.type});
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                const contentDisposition = response.headers['content-disposition'];
                let fileName = 'hasil-verifikasi.xlsx';
                if (contentDisposition) {
                    const fileNameMatch = contentDisposition.match(/filename=(.+)/);
                    if (fileNameMatch.length === 2)
                        fileName = fileNameMatch[1];
                }
                link.setAttribute('download', fileName);
                document.body.appendChild(link);
                link.click();
                link.remove();
                window.URL.revokeObjectURL(url);
                this.isLoadingExp = false;
            });

      } catch (err) {
        if (err.response && err.response.data.code == 422) {
          this.$nextTick(() => {
            this.form.errors.set(err.response.data.error);
          });
          this.$toast.error("Mohon cek kembali formulir Anda", {
            icon: "times",
            iconPack: "fontawesome",
            duration: 5000
          });
        } else {
          this.$swal.fire(
            "Terjadi kesalahan",
            "Silakan hubungi Admin",
            "error"
          );
        }
      }
      this.loading = false;
    }
  }
};
</script>
