<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Hasil Pemeriksaan</portal>
    <portal to="title-action">
      <div class="title-action">
        <button v-if="user && user.role_id !== 1 && user.role_id !== 2" tag="button" class="btn btn-import-export" data-toggle="modal"
          data-target="#importHasil">
          <i class="fa fa-download" /> Import
        </button>
        <download-export-button :parentRefs="$refs"
          :ajaxTableRef="user && user.role_id === 1 || user.role_id === 2 ? 'verifikasi-admin' : 'verifikasi'" class="btn btn-primary" />
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <filter-hasil-pemeriksaan :oid="user && user.role_id === 1 || user.role_id === 2 ? 'verifikasi-admin' : 'verifikasi'" />
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Sampel Hasil Pemeriksaan">
          <ajax-table url="/v1/verifikasi/list" urlexport="/v1/verifikasi/export" :params="params1"
            :disableSort="['parameter_lab']"
            :rowtemplate="user && user.role_id === 1 || user.role_id === 2 ? 'tr-hasil-pemeriksaan-admin' : 'tr-verifikasi'"
            :ref="user && user.role_id === 1 || user.role_id === 2 ? 'verifikasi-admin' : 'verifikasi'"
            :oid="user && user.role_id === 1 || user.role_id === 2 ? 'verifikasi-admin' : 'verifikasi'"
            :columns="user && user.role_id === 1 || user.role_id === 2 ? colSuperAdmin : colAdminSatelit" :config="{
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
            }" />
        </Ibox>
      </div>
    </div>

    <custom-modal modal_id="importHasil" title="Import Data">
      <div slot="body">
        <div class="col-lg-12">
          <div class="form-group">
            <dropzone-import-excel :previewFile="previewFile" />
          </div>
          <div class="form-group">
            <button @click="doImport()" :disabled="loading" :class="{'btn-loading': loading}"
              class="btn btn-md btn-primary block m-b pull-right" type="button">
              <i class="fa fa-check" /> Import Excel
            </button>
          </div>
          <br>
          <div class="form-group">
            <label class="text-muted" style="text-align: justify">
              Berikut adalah contoh format untuk import excel Hasil Pemeriksaan, data wilayah, dan data fasyankes yang
              dapat diunduh
              sebagai referensi.
            </label>
            <button @click="downloadFormat('formatHasilPemeriksaan')" :disabled="loading"
              :class="{'btn-loading': loading}" class="btn btn-sm btn-default" type="button">
              <i class="fa fa-file" /> Format Import
            </button>
            <button @click="downloadFormat('wilayah')" :disabled="loading" :class="{'btn-loading': loading}"
              class="btn btn-sm btn-default" type="button">
              <i class="fa fa-file" /> Data Wilayah
            </button>
            <button @click="downloadFormat('fasyankes')" :disabled="loading" :class="{'btn-loading': loading}"
              class="btn btn-sm btn-default" type="button">
              <i class="fa fa-file" /> Data Fasyankes
            </button>
          </div>
        </div>
      </div>
    </custom-modal>

  </div>
</template>

<script>
  import {
    mapGetters
  } from "vuex";
  import axios from "axios";
  import Form from "vform";
  import $ from "jquery";
  import CustomModal from "~/components/CustomModal";
  import {
    colAdminSatelit,
    colSuperAdmin,
  } from '~/assets/js/constant/enum';
  const JQuery = $;
  var debounce = require('lodash/debounce')

  export default {
    middleware: ['auth', 'checkrole'],
    meta: {
      allow_role_id: [1, 2, 8]
    },
    components: {
      CustomModal,
    },
    computed: mapGetters({
      user: "auth/user"
    }),
    data() {
      let selectedNomorSampels = this.$store.state.hasil_pemeriksaan.selectedSampels;
      return {
        loading: false,
        dataError: [],
        params1: {
          fasyankes: "",
          kota_domisili: "",
          tanggal_verifikasi_start: "",
          tanggal_verifikasi_end: "",
          kesimpulan_pemeriksaan: "",
          kategori: "",
          id: [],
        },
        selectedNomorSampels,
        colAdminSatelit,
        colSuperAdmin,
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
        this.refreshTable()
      }, 500),
      async doImport() {
        let formData = new FormData();
        formData.append('register_file', this.form.register_file);
        this.loading = true;
        JQuery('#importHasil').modal('hide');
        try {
          await axios.post(process.env.apiUrl + "/v1/register/import-hasil-pemeriksaan", formData, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          });
          this.refreshTable()
          this.$toast.success('Sukses import data', {
            icon: "check",
            iconPack: "fontawesome",
            duration: 5000
          });
        } catch (err) {
          if (err.response && err.response.data.code == 422) {
            for (const property in err.response.data.error) {
              this.$toast.error(err.response.data.error[property][0], {
                icon: "times",
                iconPack: "fontawesome",
                duration: 5000
              });
            }
          } else {
            this.$swal.fire(
              "Terjadi kesalahan",
              "cek kembali file import",
              "error"
            );
          }
        }
        this.$bus.$emit('reset-importfile');
        $('#register_file').val('');
        this.form.reset();
        this.loading = false;
      },
      previewFile(file) {
        this.form.register_file = file;
      },
      refreshTable() {
        if (this.user && this.user.role_id === 1 || this.user.role_id === 2) {
          this.$bus.$emit("refresh-ajaxtable", "verifikasi-admin")
        } else {
          this.$bus.$emit("refresh-ajaxtable", "verifikasi")
        }
      }
    },
    watch: {
      'selectedNomorSampels': function () {
        const idArray = []
        for (let i = 0; i < this.selectedNomorSampels.length; i++) {
          idArray.push(this.selectedNomorSampels[i].id)
        }
        this.params1.id = idArray
      },
      "params1.fasyankes": function (newVal, oldVal) {
        this.refreshTable()
      },
      "params1.kota_domisili": function (newVal, oldVal) {
        this.refreshTable()
      },
      "params1.tanggal_verifikasi_start": function (newVal, oldVal) {
        this.refreshTable()
      },
      "params1.tanggal_verifikasi_end": function (newVal, oldVal) {
        this.refreshTable()
      },
      "params1.kesimpulan_pemeriksaan": function (newVal, oldVal) {
        this.refreshTable()
      },
    },
  };
</script>