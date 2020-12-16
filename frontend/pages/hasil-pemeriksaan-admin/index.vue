<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Hasil Pemeriksaan</portal>
    <portal to="title-action">
      <div class="title-action">
        <download-export-button :parentRefs="$refs" ajaxTableRef="verifikasi-admin" class="btn btn-primary" />
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <filter-hasil-pemeriksaan :oid="`verifikasi-admin`" />
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Sampel Hasil Pemeriksaan">
          <ajax-table ref="verifikasi-admin" url="/v1/verifikasi/list" urlexport="/v1/verifikasi/export"
            :disableSort="['parameter_lab']" :oid="'verifikasi-admin'" :params="params1" :config="{
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
            }" :rowtemplate="'tr-hasil-pemeriksaan-admin'" :columns="{
							checkbox_sampel_id: '#',
              waktu_pcr_sample_analyzed: 'TANGGAL PEMERIKSAAN',
              nomor_sampel : 'NO SAMPEL',
              pasien_nama : 'NAMA PASIEN',
              kota_domilisi: 'DOMISILI',
              instansi_pengirim: 'INSTANSI',
              lab: 'LAB PEMERIKSA',
              sumber_pasien: 'KATEGORI',
              kesimpulan_pemeriksaan: 'KESIMPULAN PEMERIKSAAN',
            }" />
        </Ibox>
      </div>
    </div>

  </div>
</template>

<script>
  import axios from "axios";
  import Form from "vform";
  import $ from "jquery";
  import CustomModal from "~/components/CustomModal";
  const JQuery = $;
  var debounce = require('lodash/debounce')

  export default {
    middleware: ['auth', 'checkrole'],
    meta: {
      allow_role_id: [1]
    },
    components: {
      CustomModal,
    },
    data() {
      let selectedNomorSampels = this.$store.state.hasil_pemeriksaan.selectedSampels;
      return {
        loading: false,
        dataError: [],
        params1: {
          fasyankes: "",
          kota_domisili: "",
          tanggal_verifikasi_start: "",
          lab: "",
          tanggal_verifikasi_end: "",
          kesimpulan_pemeriksaan: "",
          kategori: "",
          id: selectedNomorSampels && selectedNomorSampels.length !== 0 ? selectedNomorSampels : [],
        },
        selectedNomorSampels,
      };
    },
    async asyncData({
      route,
      store
    }) {
      let form = new Form({
        register_file: null
      });
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
        form,
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
        this.$bus.$emit("refresh-ajaxtable", "verifikasi-admin");
      },
      "params1.kota_domisili": function (newVal, oldVal) {
        this.$bus.$emit("refresh-ajaxtable", "verifikasi-admin");
      },
      "params1.tanggal_verifikasi_start": function (newVal, oldVal) {
        this.$bus.$emit("refresh-ajaxtable", "verifikasi-admin");
      },
      "params1.tanggal_verifikasi_end": function (newVal, oldVal) {
        this.$bus.$emit("refresh-ajaxtable", "verifikasi-admin");
      },
      "params1.kesimpulan_pemeriksaan": function (newVal, oldVal) {
        this.$bus.$emit("refresh-ajaxtable", "verifikasi-admin");
      },
    },
    methods: {
      downloadFormat(namaFile) {
        this.$axios.get(`v1/download?namaFile=${namaFile}`, {
            responseType: 'blob'
          })
          .then(response => {
            let blob = new Blob([response.data], {
              type: response.headers['content-type']
            })
            let link = document.createElement('a')
            link.href = window.URL.createObjectURL(blob)
            link.download = namaFile + '.xlsx'
            link.setAttribute('download', link.download);
            document.body.appendChild(link);
            link.click()
            window.URL.revokeObjectURL(link.href);
            link.remove();
          });
      },
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
        this.$bus.$emit('refresh-ajaxtable', 'verifikasi-admin')
      }, 500),
      previewFile(file) {
        this.form.register_file = file;
      }
    },
    watch: {
      'selectedNomorSampels': function (newVal, oldVal) {
        this.params1.id = this.selectedNomorSampels && this.selectedNomorSampels.length !== 0 ? this.selectedNomorSampels : []
      }
    }
  };
</script>