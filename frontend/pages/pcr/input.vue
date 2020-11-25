<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Input Hasil</portal>
    <portal to="title-action">
      <div class="title-action">
        <a href="#" @click.prevent="$router.back()" class="btn btn-secondary">
          <i class="uil-arrow-left" />Kembali
        </a>
      </div>
    </portal>
    <form @submit.prevent="dummy">
      <div class="row">
        <div class="col-md-10">
          <Ibox title="Informasi Sampel">
            <div class="form-group row">
              <div class="col-md-3 flex-text-center">
                Nomor Sampel
              </div>
              <div class="col-md-8">
                {{ data.nomor_sampel || null }}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 flex-text-center">
                Nama
              </div>
              <div class="col-md-8">
                {{ data.pasien.nama_lengkap || null }}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 flex-text-center">
                NIK
              </div>
              <div class="col-md-8">
                {{ data.pasien.nik || null }}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 flex-text-center">
                Instansi Pengirim
              </div>
              <div class="col-md-8">
                {{ data.register.instansi_pengirim || null }}
              </div>
            </div>
          </Ibox>
        </div>

        <div class="col-md-10">
          <Ibox title="Hasil Analisa PCR">
            <div class="form-group row">
              <div class="col-md-3 flex-text-center">
                Kit Pemeriksaan
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <input type="text" class="form-control" v-model="form.nama_kit_pemeriksaan"
                  :class="{ 'is-invalid': form.errors.has(`nama_kit_pemeriksaan`) }">
                <has-error :form="form" field="nama_kit_pemeriksaan" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3 flex-text-center">
                Tanggal Input Hasil
              </div>
              <div class="col-md-8">
                <date-picker :disabled="true" placeholder="Pilih Tanggal" format="d MMMM yyyy"
                  input-class="form-control" :monday-first="true"
                  :wrapper-class="{ 'is-invalid': form.errors.has(`tanggal_input_hasil`) }"
                  v-model="form.tanggal_input_hasil" />
                <has-error :form="form" field="tanggal_input_hasil" />
              </div>
            </div>

            <table class="table table-striped dt-responsive table-bordered" style="width: 91.5%">
              <thead>
                <tr>
                  <th>Target Gen</th>
                  <th>CT Value</th>
                  <th></th>
                </tr>
              </thead>
              <tbody class="field_wrapper">
                <tr v-for="(hasil, $index) in form.hasil_deteksi" :key="$index">
                  <td>
                    <input class="form-control" type="text" v-model="hasil.target_gen"
                      :class="{ 'is-invalid': form.errors.has(`hasil_deteksi.${$index}.target_gen`) }" />
                    <has-error :form="form" :field="`hasil_deteksi.${$index}.target_gen`" />
                  </td>
                  <td>
                    <input class="form-control" type="text" v-model="hasil.ct_value"
                      :class="{ 'is-invalid': form.errors.has(`hasil_deteksi.${$index}.ct_value`) }" />
                    <has-error :form="form" :field="`hasil_deteksi.${$index}.ct_value`" />
                  </td>
                  <td>
                    <button class="btn btn-sm btn-clear" type="button" @click.prevent="removeHasilDeteksi($index)">
                      <i class="uil-trash" />
                    </button>
                  </td>
                </tr>
                <tr>
                  <td></td>
                  <td colspan="2">
                    <button class="btn btn-sm btn-import-export" type="button" @click.prevent="addHasilDeteksi()">
                      <i class="fa fa-plus" /> Tambah Hasil CT
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>

            <div class="form-group row">
              <div class="col-md-3">
                Kesimpulan Pemeriksaan
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8" :class="{ 'is-invalid': form.errors.has('kesimpulan_pemeriksaan') }">
                <div>
                  <label class="form-check-label">
                    <input type="radio" v-model="form.kesimpulan_pemeriksaan" value="positif" />
                    <b>POSITIF</b>
                  </label>
                </div>
                <div>
                  <label class="form-check-label">
                    <input type="radio" v-model="form.kesimpulan_pemeriksaan" value="negatif" />
                    <b>NEGATIF</b>
                  </label>
                </div>
                <div>
                  <label class="form-check-label">
                    <input type="radio" v-model="form.kesimpulan_pemeriksaan" value="inkonklusif" />
                    <b>INKONKLUSIF</b>
                  </label>
                </div>
                <has-error :form="form" field="kesimpulan_pemeriksaan" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-3">
                Catatan Pemeriksaan
              </div>
              <div class="col-md-8">
                <textarea class="form-control" v-model="form.catatan_pemeriksaan"
                  :class="{ 'is-invalid': form.errors.has(`catatan_pemeriksaan`) }"></textarea>
                <has-error :form="form" field="catatan_pemeriksaan" />
              </div>
            </div>

            <div class="form-group">
              <button @click="submit()" :class="{'btn-loading': loading}"
                class="btn btn-md btn-primary block full-width m-b" type="button">
                <i class="fa fa-check" />
                Submit Hasil Pemeriksaan
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
    async asyncData({
      route,
      store
    }) {
      let resp = await axios.get("/v1/pcr/detail/" + route.params.id);
      let data = resp.data.data;
      if (!data.log_pcr) {
        data.log_pcr = [];
      }
      if (!data.ekstraksi) {
        data.ekstraksi = {};
      }
      if (!data.pcr) {
        data.pcr = {};
      }
      let dz_options = {
        url: process.env.apiUrl + "/v1/pcr/upload-grafik",
        headers: {
          Authorization: `Bearer ${store.getters["auth/token"]}`
        },
        dictDefaultMessage: "Klik disini untuk mengunggah file",
        acceptedFiles: ".jpeg,.jpg,.png,.gif",
        maxFilesize: 16,
        addRemoveLinks: true
      };
      let default_hasil_deteksi = data.pcr.nama_kit_pemeriksaan == 'Liferiver' ? [{
          target_gen: 'ORF1AB'
        }, {
          target_gen: 'N'
        }, {
          target_gen: 'E'
        }, {
          target_gen: 'IC'
        }] :
        (data.pcr.nama_kit_pemeriksaan == 'Alplex' ? [{
          target_gen: 'RrDP'
        }, {
          target_gen: 'N'
        }, {
          target_gen: 'E'
        }, {
          target_gen: 'IC'
        }] : [{}])
      let form = new Form({
        tanggal_input_hasil: new Date(),
        catatan_pemeriksaan: null,
        kesimpulan_pemeriksaan: null,
        hasil_deteksi: [{
          target_gen: null,
          ct_value: null
        }],
        nama_kit_pemeriksaan: null,
        sampel: data.sampel
      });
      return {
        data,
        dz_options,
        form,
      };
    },
    head() {
      return {
        title: "Input Analisa RT-PCR"
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
          const response = await this.form.post("/v1/pcr/input/" + this.$route.params.id);
          this.$toast.success(response.data.message, {
            icon: "check",
            iconPack: "fontawesome",
            duration: 5000
          });
          this.$router.back()
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
      addHasilDeteksi() {
        this.form.hasil_deteksi.push({
          ct_value: null,
          target_gen: null
        });
      },
      removeHasilDeteksi(index) {
        if (this.form.hasil_deteksi.length <= 1) {
          this.$toast.error("Hasil deteksi minimal satu", {
            duration: 5000
          });
          return;
        }
        this.form.hasil_deteksi.splice(index, 1);
      },
      onFileSuccess(file, resp) {
        this.form.grafik.push(resp.url);
      }
    }
  };
</script>