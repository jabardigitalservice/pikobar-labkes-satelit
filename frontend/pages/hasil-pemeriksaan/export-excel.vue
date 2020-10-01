<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Export Hasil Verifikasi (.xlsx)</portal>
    <portal to="title-action">
      <div class="title-action">
        <a href="#" @click.prevent="$router.back()" class="btn btn-secondary">
          <i class="uil-arrow-left"></i>Kembali
        </a>
      </div>
    </portal>
    <form @submit.prevent="dummy">
      <div class="row">
        
        <div class="col-md-6">
          <Ibox title="Export">

            <div class="form-group">
              <label>
                Tanggal registrasi (awal)
              </label>
              <date-picker
                placeholder="Pilih Tanggal"
                format="d MMMM yyyy"
                input-class="form-control"
                :monday-first="true"
                :wrapper-class="{ 'is-invalid': form.errors.has(`start_date`) }"
                v-model="form.start_date"
              />
              <has-error :form="form" field="start_date" />
            </div>

            <div class="form-group">
              <label>
                Tanggal registrasi (akhir)
              </label>
              <date-picker
                placeholder="Pilih Tanggal"
                format="d MMMM yyyy"
                input-class="form-control"
                :monday-first="true"
                :wrapper-class="{ 'is-invalid': form.errors.has(`end_date`) }"
                v-model="form.end_date"
              />
              <has-error :form="form" field="end_date" />
            </div>

            <div class="form-group">
              <button
                @click="submit()"
                :disabled="loading"
                :class="{'btn-loading': loading}"
                class="btn btn-md btn-primary block full-width m-b"
                type="button"
              >
                <i class="fa fa-check"></i>
                Download Excel
              </button>
            </div>
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
      loading: false
    };
  },

  async asyncData({ route, store }) {

    let form = new Form({
      start_date: new Date(),
      end_date: new Date()
    });

    return {
      form,
    };
  },
  head() {
    return {
      title: "Export Excel Hasil Verifikasi"
    };
  },
  methods: {
    dummy() {
      return false;
    },
    async submit() {
      // Submit the form.
      try {
        this.loading = true;

        axios({
                url: process.env.apiUrl + "/v1/verifikasi/export-excel",
                params: this.form,
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