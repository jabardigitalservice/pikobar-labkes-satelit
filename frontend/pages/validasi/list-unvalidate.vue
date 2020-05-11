<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Menunggu Validasi</portal>

    <div class="row">
      <div class="col-lg-12">

        <Ibox title="Sampel Menunggu Verifikasi">
          <p class="sub-header">
            Berikut ini adalah daftar sampel dari hasil verifikasi sampel</p>

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
              <!-- <div class="col-md-3">
                <div class="form-group">
                  <label>Tanggal Validasi (Awal)</label>
                  <date-picker
                    placeholder="Pilih Tanggal"
                    format="d MMMM yyyy"
                    input-class="form-control"
                    :monday-first="true"
                    v-model="params1.tanggal_validasi_start"
                  />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Tanggal Validasi (Akhir)</label>
                  <date-picker
                    placeholder="Pilih Tanggal"
                    format="d MMMM yyyy"
                    input-class="form-control"
                    :monday-first="true"
                    v-model="params1.tanggal_validasi_end"
                  />
                </div>
              </div> -->
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
              <div class="col-md-3">
                <div class="form-group">
                  <label>Kategori</label>
                  <dynamic-input :form="params1" field="kategori" v-model="params1.kategori"
                    :options="listKategori"
                    :hasSemua="true">
                  </dynamic-input>
                </div>
              </div>
              <div class="col-md-6">
                
                <button id="btn-export" class="btn btn-primary pull-right mt-4" 
                  @click="onExport('validasi')"
                >
                  <i class="fa fa-file-excel-o"></i> Export Excel
                </button>

                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary pull-right mt-4 mr-2" 
                  data-toggle="modal" data-target="#modalBulkValidate"
                >
                  <i class="fa fa-check"></i>
                  Validasi
                </button>

              </div>
              
            </div>

          <ajax-table
            url="/v1/validasi/list"
            :oid="'validasi'"
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
                :rowtemplate="'tr-validasi'"
                :columns="{
                      checkbox_input: '#',
                      nomor_register: 'Nomor Register',
                      pasien_nama : 'Nama Pasien',
                      kota_nama : 'Kota Domisili',
                      nomor_sampel : 'Nomor Sampel',
                      parameter_lab: 'Parameter Lab',
                      kondisi_sampel: 'Kondisi Sampel',
                      kesimpulan_pemeriksaan: 'Kesimpulan Pemeriksaan',
                    }"
          ></ajax-table>
        </Ibox>
      </div>
    </div>

    <custom-modal 
      modal_id="modalBulkValidate" 
      title="Validasi Sampel"
    >
      <div slot="body">

        <div class="alert alert-danger" v-show="sampelIds.length < 1">
          <strong>
            Perhatian!
          </strong>
            Belum ada sampel yang dipilih. Silahkan centang beberapa pada tabel.
        </div>

        <form @submit.prevent="dummy">
          <div class="row">
            <div class="form-group">
              <label>
                Pejabat yang Menandatangani Hasil Lab yang tercetak
                <span style="color:red">*</span>
              </label>
              <div :class="{ 'is-invalid': form.errors.has('validator') }">
                <div v-for="item in listValidator" :key="item.id">
                  <label class="form-check-label">
                    <input type="radio" v-model="form.validator" :value="item.id" />
                    <b>{{item.nama}}</b> (NIP. {{ item.nip }})
                  </label>
                </div>
              </div>
              <has-error :form="form" field="validator" />
            </div>
          </div>
          <div class="row">
            <button
                @click="submit()"
                :disabled="loading"
                :class="{'btn-loading': loading}"
                class="btn btn-md btn-primary block full-width m-b"
                type="button"
              >
                <i class="fa fa-check"></i>
                {{ 'Validasi' }}
              </button>
          </div>
        </form>
      </div>
    </custom-modal>

  </div>
</template>
 
<script>
import Form from "vform";
import axios from "axios";
import JQuery from "jquery";
import CustomModal from "~/components/CustomModal";

export default {
  middleware: "auth",
  components: {
    CustomModal
  },
  data() {
    return {
      params1: {
        fasyankes: "",
        kota_domisili: "",
        tanggal_validasi_start: "",
        tanggal_validasi_end: "",
        kesimpulan_pemeriksaan: "",
        kategori: ""
      },
      loading: false
    };
  },
  async asyncData({ route, store }) {

    let respListValidator = await axios.get("/v1/validasi/list-validator");
    let listValidator = respListValidator.data.data;
    let sampelIds = store.state.validasi.selectedSampels;
    let listKategori = await axios.get("v1/verifikasi/list-kategori");


    let form = new Form({
      validator: null,
      sampels: sampelIds
    });

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

    if (listKategori.data.data) {
      listKategori = listKategori.data.data.map(function(kategori, index){
        let newKategori = kategori;
        newKategori.name = kategori.sumber_pasien;
        newKategori.id = kategori.sumber_pasien;

        return newKategori;
      })
    }

    return {
      form,
      listValidator,
      sampelIds,
      listKota,
      listFasyankes,
      listKategori
    };
  },
  head() {
    return { title: "Sampel Hasil Pemeriksaan" };
  },
  watch: {
    "params1.fasyankes": function(newVal, oldVal) {
      this.$bus.$emit("refresh-ajaxtable", "validasi");
    },
    "params1.kota_domisili": function(newVal, oldVal) {      
      this.$bus.$emit("refresh-ajaxtable", "validasi");
    },
    "params1.tanggal_validasi_start": function(newVal, oldVal) {
      this.$bus.$emit("refresh-ajaxtable", "validasi");
    },
    "params1.tanggal_validasi_end": function(newVal, oldVal) {
      this.$bus.$emit("refresh-ajaxtable", "validasi");
    },
    "params1.kesimpulan_pemeriksaan": function(newVal, oldVal) {
      this.$bus.$emit("refresh-ajaxtable", "validasi");
    },
    "params1.kategori": function(newVal, oldVal) {
      this.$bus.$emit("refresh-ajaxtable", "validasi");
    },
  },
  methods: {
    dummy() {
      return false;
    },
    async submit() {
      // Submit the form.
      try {

        this.loading = true;

        const response = await this.form.post("/v1/validasi/bulk-validasi");

        this.$toast.success(response.data.message, {
          icon: "check",
          iconPack: "fontawesome",
          duration: 5000
        });

        // clear selected checkbox sampel
        this.$store.commit('validasi/clear');

        this.$bus.$emit('refresh-ajaxtable', 'validasi');

        JQuery('#modalBulkValidate').modal('hide');

        // this.$router.replace({name: 'validasi.index.validated'});

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
    },
    async onExport(type){
      try {
        this.loading = true;

        let form = new Form({...this.params1, type: type})

        axios({
            url: process.env.apiUrl + "/v1/validasi/export-excel",
            params: form,
            method: 'GET',
            responseType: 'blob',
        }).then((response) => {
            const blob = new Blob([response.data], {type: response.data.type});
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            const contentDisposition = response.headers['content-disposition'];
            let fileName = 'belum-validasi.xlsx';
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
