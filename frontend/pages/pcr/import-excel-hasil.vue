<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Import Input Hasil PCR Laboratorium</portal>
    <portal to="title-action">
      <div class="title-action">
        <a href="#" @click.prevent="$router.back()" class="btn btn-secondary">
          <i class="uil-arrow-left"></i>Kembali
        </a>
      </div>
    </portal>
    <form @submit.prevent="dummy">
      <div class="row">
        <div class="col-md-5">
          <Ibox title="Import Data">
            <div class="form-group">
              <dropzone-import-excel :previewFile="previewFile" />
            </div>

            <div class="form-group">
              <button @click="submit()" :disabled="loading" :class="{'btn-loading': loading}"
                class="btn btn-md btn-default block full-width m-b" type="button">
                <i class="fa fa-search" /> Baca Excel
              </button>
            </div>
            <hr><br>
            <div class="form-group" v-if="errors_count == 0 && data.length > 0">
              Terdapat {{ data.length }} data
            </div>
            <div class="form-group" v-if="errors_count > 0">
              Terdapat {{ errors_count }} error. Mohon periksa file Anda
            </div>

            <div class="form-group">
              <button @click="submitData()" :disabled="loading || data.length == 0 || errors_count > 0"
                :class="{'btn-loading': loading}" class="btn btn-md btn-primary block full-width m-b" type="button">
                <i class="fa fa-check" /> Import Excel
              </button>
              <div class="form-group">
                <label class="text-muted" style="text-align: justify">
                  Berikut adalah contoh format untuk Import Excel Input Hasil PCR Laboratorium yang dapat diunduh
                  sebagai referensi.
                </label>
                <button @click="downloadFormat('formatInputHasil')" :disabled="loading"
                  :class="{'btn-loading': loading}" class="btn btn-sm btn-default" type="button">
                  <i class="fa fa-file" /> Format Import
                </button>
              </div>
            </div>
          </Ibox>
        </div>
        <div class="col-md-7" v-if="data.length > 0">
          <Ibox title="Pratijau Import Data">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nomor Sampel</th>
                  <th>Target Gen</th>
                  <th>Kesimpulan Pemeriksaan</th>
                  <th>Tanggal Periksa</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody>
                <template v-for="(row, $index) in data">
                  <tr :key="row.nomor_sampel">
                    <td>{{ row.no }}</td>
                    <td>{{ row.nomor_sampel }}</td>
                    <td>
                      <div v-for="(value) in row.target_gen" :key="value.target_gen">- {{ value.target_gen }}:
                        {{ value.ct_value }}</div>
                    </td>
                    <td>{{ row.kesimpulan_pemeriksaan }}</td>
                    <td>{{ row.tanggal_input_hasil }}</td>
                    <td>{{ row.catatan_pemeriksaan }}</td>
                  </tr>
                  <tr :key="$index" class="bg-warning" v-if="errors[$index]">
                    <td>Error</td>
                    <td colspan="5">
                      <div v-for="(error, $index2) in errors[$index]" :key="$index2">- {{ error }}</div>
                    </td>
                  </tr>
                </template>
              </tbody>
            </table>
          </Ibox>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
  import Form from "vform";
  import axios from "axios";

  export default {
    middleware: "auth",

    data() {
      return {
        loading: false,
        data: [],
        errors: {},
        errors_count: 0,
      };
    },

    async asyncData({
      route,
      store
    }) {
      let form = new Form({
        register_file: null
      });

      return {
        form
      };
    },

    head() {
      return {
        title: "Import Hasil PCR Laboratorium"
      };
    },
    methods: {
      dummy() {
        return false;
      },
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
      async submit() {
        let formData = new FormData();
        formData.append("register_file", this.form.register_file);
        this.loading = true;

        try {
          let resp = await axios.post("/v1/pcr/import-hasil-pemeriksaan", formData, {
            headers: {
              "Content-Type": "multipart/form-data"
            }
          });

          this.$toast.success("Mohon cek data ini terlebih dahulu", {
            icon: "check",
            iconPack: "fontawesome",
            duration: 5000
          });
          this.data = resp.data.data
          this.errors = resp.data.errors
          this.errors_count = resp.data.errors_count
        } catch (err) {
          this.$swal.fire(
            "Terjadi kesalahan",
            "cek kembali file import",
            "error"
          );
        }
        this.$bus.$emit('reset-importfile');
        this.loading = false;
      },
      async submitData() {
        // Submit the form.
        this.$bus.$emit('reset-importfile');
        try {
          this.loading = true;
          const response = await axios.post("/v1/pcr/import-data-hasil-pemeriksaan", {
            data: this.data
          });
          this.$toast.success(response.data.message, {
            icon: "check",
            iconPack: "fontawesome",
            duration: 5000
          });
          this.$router.back()
        } catch (err) {
          this.$swal.fire(
            "Terjadi kesalahan",
            "Silakan hubungi Admin",
            "error"
          );
        }
      },
      previewFile(file) {
        this.form.register_file = file;
      }
    }
  };
</script>