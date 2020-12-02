<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Registrasi Sampel
    </portal>
    <portal to="title-action">
      <div class="title-action">
        <nuxt-link tag="button" to="/registrasi/perujuk/tambah" class="btn btn-primary">
          <i class="fa fa-plus" /> Register Baru
        </nuxt-link>
        <button tag="button" class="btn btn-import-export" data-toggle="modal" data-target="#importRegPerujuk">
          <i class="fa fa-download" /> Import
        </button>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <filter-perujuk :oid="`registrasi-sampel-perujuk`" />
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Register Pasien">
          <ajax-table url="v1/register-perujuk" :oid="'registrasi-sampel-perujuk'" :params="params"
            :disableSort="['keterangan']" :config="{
              autoload: true,
              has_number: true,
              has_entry_page: true,
              has_pagination: true,
              has_action: true,
              has_search_input: false,
              custom_header: '',
              default_sort: 'tgl_input',
              default_sort_dir:'desc',
              custom_empty_page: true,
              class: {
                table: [],
                wrapper: ['table-responsive'],
              }
            }" :rowtemplate="'tr-data-regis-perujuk'" :columns="{
              no_sampel:'SAMPEL',
              kode_kasus : 'KODE KASUS',
              nama_pasien: 'PASIEN',
              nama_kota: 'DOMISILI',
              sumber_pasien:'KATEGORI',
              status:'STATUS',
              keterangan:'KETERANGAN'
            }" />
        </Ibox>
      </div>
    </div>

    <custom-modal modal_id="importRegPerujuk" title="Import Data">
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
              Berikut adalah contoh format untuk import excel Registrasi perujuk dan Data Lab Satelit yang
              dapat diunduh sebagai referensi.
            </label>
            <button @click="downloadFormat('formatRegistrasiPerujuk')" :disabled="loading"
              :class="{'btn-loading': loading}" class="btn btn-sm btn-default" type="button">
              <i class="fa fa-file" /> Format Import
            </button>
            <button @click="downloadFormat('labSatelit')" :disabled="loading"
              :class="{'btn-loading': loading}" class="btn btn-sm btn-default" type="button">
              <i class="fa fa-file" /> Data Lab Satelit
            </button>
          </div>
        </div>
      </div>
    </custom-modal>

  </div>
</template>

<script>
  import Form from "vform";
  import $ from "jquery";
  import CustomModal from "~/components/CustomModal";
  const JQuery = $;
  export default {
    middleware: ['auth', 'checkrole'],
    meta: {
      allow_role_id: [9]
    },
    components: {
      CustomModal,
    },
    data() {
      return {
        loading: false,
        dataError: [],
        params: {
          nama_pasien: '',
          nomor_register: '',
          nomor_sampel: '',
          start_date: '',
          end_date: ''
        },
      }
    },
    head() {
      return {
        title: this.$t('home')
      }
    },
    async asyncData({
      route,
      store
    }) {
      let form = new Form({
        register_file: null
      });
      return {
        form,
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
      async doImport() {
        let formData = new FormData();
        formData.append('register_file', this.form.register_file);
        this.loading = true;
        JQuery('#importRegPerujuk').modal('hide');
        try {
          await this.$axios.post(`${process.env.apiUrl}/v1/register-perujuk/import`, formData, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          });
          this.$bus.$emit('refresh-ajaxtable', 'registrasi-sampel-perujuk');
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
        this.form.register_file = null;
        this.loading = false;
      },
      previewFile(file) {
        this.form.register_file = file;
      }
    }
  }
</script>