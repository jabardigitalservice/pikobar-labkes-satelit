<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Hasil Pemeriksaan</portal>
    <div class="row">
      <div class="col-lg-12">
        <filter-hasil-pemeriksaan :oid="`verifikasi`" />
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Sampel Hasil Pemeriksaan">
          <div class="row mb-4">
            <div class="col-md-12">
              <download-export-button :parentRefs="$refs" ajaxTableRef="verifikasi"
                class="btn btn-primary pull-right ml-1"></download-export-button>
              <router-link to="/hasil-pemeriksaan/import-excel" class="btn btn-primary pull-right ">
                <i class="fa fa-file-excel-o"></i> Import Excel
              </router-link>
            </div>

          </div>

          <ajax-table ref="verifikasi" url="/v1/verifikasi/list" urlexport="/v1/verifikasi/export"
            :disableSort="['parameter_lab']" :oid="'verifikasi'" :params="params1" :config="{
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
                    }" :rowtemplate="'tr-verifikasi'" :columns="{
                      waktu_pcr_sample_analyzed: 'Tanggal Pemeriksaan',
                      nomor_sampel : 'Nomor Sampel',
                      pasien_nama : 'Nama Pasien',
                      kota_domilisi: 'Kota Domisili',
                      instansi_pengirim: 'Nama Rumah Sakit/Dinkes',
                      parameter_lab: 'Parameter Lab',
                      status: 'Status',
                      sumber_pasien: 'Kategori',
                      kesimpulan_pemeriksaan: 'Kesimpulan Pemeriksaan',
                      catatat: 'Keterangan',
                    }"></ajax-table>
        </Ibox>
      </div>
    </div>
  </div>
</template>

<script>
  import axios from "axios";
  import Form from "vform";
  var debounce = require('lodash/debounce')

  export default {
    middleware: ['auth', 'checkrole'],
    meta: {
      allow_role_id: [8]
    },
    data() {
      return {
        params1: {
          fasyankes: "",
          kota_domisili: "",
          tanggal_verifikasi_start: "",
          tanggal_verifikasi_end: "",
          kesimpulan_pemeriksaan: "",
          kategori: ""
        }
      };
    },
    async asyncData({
      route,
      store
    }) {
      let listKota = await axios.get("/v1/list-kota-jabar");
      let listFasyankes = await axios.get("v1/list-fasyankes-jabar");
      let listKategori = await axios.get("v1/verifikasi/list-kategori");

      if (listKota.data) {
        listKota = listKota.data.map(function (kota, index) {
          let newKota = kota;
          newKota.name = kota.nama;

          return newKota;
        })
      }

      if (listFasyankes.data) {
        listFasyankes = listFasyankes.data.map(function (fasyan, index) {
          let newFasyankes = fasyan;
          newFasyankes.name = fasyan.nama;

          return newFasyankes;
        })
      }

      if (listKategori.data.data) {
        listKategori = listKategori.data.data.map(function (kategori, index) {
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
      return {
        title: "Sampel Hasil Pemeriksaan"
      };
    },
    watch: {
      "params1.fasyankes": function (newVal, oldVal) {
        this.$bus.$emit("refresh-ajaxtable", "verifikasi");
      },
      "params1.kota_domisili": function (newVal, oldVal) {
        this.$bus.$emit("refresh-ajaxtable", "verifikasi");
      },
      "params1.tanggal_verifikasi_start": function (newVal, oldVal) {
        this.$bus.$emit("refresh-ajaxtable", "verifikasi");
      },
      "params1.tanggal_verifikasi_end": function (newVal, oldVal) {
        this.$bus.$emit("refresh-ajaxtable", "verifikasi");
      },
      "params1.kesimpulan_pemeriksaan": function (newVal, oldVal) {
        this.$bus.$emit("refresh-ajaxtable", "verifikasi");
      },
      // "params1.kategori": function(newVal, oldVal) {
      //   this.$bus.$emit("refresh-ajaxtable", "verifikasi");
      // },
    },
    methods: {
      async onExport(type) {
        try {
          this.loading = true;

          let form = new Form({
            ...this.params1,
            type: type
          })

          axios({
            url: process.env.apiUrl + "/v1/verifikasi/export-excel",
            params: form,
            method: 'GET',
            responseType: 'blob',
          }).then((response) => {
            const blob = new Blob([response.data], {
              type: response.data.type
            });
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            const contentDisposition = response.headers['content-disposition'];
            let fileName = 'hasil_pemeriksaan.xlsx';
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


          // this.$toast.success(response.data.message, {
          //   icon: "check",
          //   iconPack: "fontawesome",
          //   duration: 5000
          // });

          // this.$router.back()

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
      refreshDebounce: debounce(function () {
        this.$bus.$emit('refresh-ajaxtable', 'verifikasi')
      }, 500),
    }
  };
</script>
