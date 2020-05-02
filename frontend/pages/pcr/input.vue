<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Input Analisa RT-PCR RNA ke Lab</portal>
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
          <Ibox title="Informasi Sampel">
            <div class="form-group">
              <label>Nomor Sampel</label>
              <p class="form-control">
                <b>{{ data.nomor_sampel }}</b>
              </p>
            </div>
            <div class="form-group">
              <label>Nomor Registrasi</label>
              <p class="form-control">
                <b>{{ data.nomor_register }}</b>
              </p>
            </div>
            <div class="form-group">
              <label>
                Tanggal Input Hasil
                <span style="color:red">*</span>
              </label>
              <date-picker
                placeholder="Pilih Tanggal"
                format="d MMMM yyyy"
                input-class="form-control"
                :disabled="true"
                :monday-first="true"
                :wrapper-class="{ 'is-invalid': form.errors.has(`tanggal_input_hasil`) }"
                v-model="form.tanggal_input_hasil"
              />
              <has-error :form="form" field="tanggal_input_hasil" />
            </div>

            <div class="form-group">
              <label>
                Jam Input Hasil
                <span style="color:red">*</span>
              </label>
              <input
                class="form-control"
                type="text"
                disabled="true"
                v-model="form.jam_input_hasil"
                v-mask="'##\:##'"
                :class="{ 'is-invalid': form.errors.has(`jam_input_hasil`) }"
              />
              <has-error :form="form" field="jam_input_hasil" />
            </div>
          </Ibox>
        </div>
        <div class="col-md-6">
          <Ibox title="Hasil Analisa PCR">
            <table class="table table-striped dt-responsive table-bordered" style="width:100%">
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
                    <input
                      class="form-control"
                      type="text"
                      v-model="hasil.target_gen"
                      :class="{ 'is-invalid': form.errors.has(`hasil_deteksi.${$index}.target_gen`) }"
                    />
                    <has-error :form="form" :field="`hasil_deteksi.${$index}.target_gen`" />
                  </td>
                  <td>
                    <input
                      class="form-control"
                      type="text"
                      v-model="hasil.ct_value"
                      :class="{ 'is-invalid': form.errors.has(`hasil_deteksi.${$index}.ct_value`) }"
                    />
                    <has-error :form="form" :field="`hasil_deteksi.${$index}.ct_value`" />
                  </td>
                  <td>
                    <button
                      class="btn btn-sm btn-danger"
                      type="button"
                      @click.prevent="removeHasilDeteksi($index)"
                    >
                      <i class="uil-trash"></i>
                    </button>
                  </td>
                </tr>
                <tr>
                  <td></td>
                  <td colspan="2">
                    <button
                      class="btn btn-sm btn-secondary"
                      type="button"
                      @click.prevent="addHasilDeteksi()"
                    >
                      <i class="fa fa-plus"></i> Tambah Hasil CT
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>

            <div class="form-group">
              <label>
                Kesimpulan Pemeriksaan
                <span style="color:red">*</span>
              </label>
              <div :class="{ 'is-invalid': form.errors.has('kesimpulan_pemeriksaan') }">
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
                    <input type="radio" v-model="form.kesimpulan_pemeriksaan" value="sampel kurang" />
                    <b>SAMPEL KURANG</b>
                  </label>
                </div>
                <div>
                  <label class="form-check-label">
                    <input type="radio" v-model="form.kesimpulan_pemeriksaan" value="inkonklusif" />
                    <b>INVALID</b>
                  </label>
                </div>
              </div>
              <has-error :form="form" field="kesimpulan_pemeriksaan" />
            </div>

            <div class="form-group">
              <label>
                Grafik
              </label>
              <div :class="{ 'is-invalid': form.errors.has('grafik') }">
                <a :href="url" target="_blank" v-for="(url, $index) in data.pcr.grafik" :key="$index" class="thumbnail-wrapper">
                  <img :src="url" />
                </a>
                <dropzone
                  id="foo"
                  ref="dz_grafik"
                  @vdropzone-success="onFileSuccess"
                  :options="dz_options"
                  :destroyDropzone="true"
                  :duplicateCheck="true"
                ></dropzone>
              </div>
              <has-error :form="form" field="grafik" />
            </div>

            <div class="form-group">
              <label>Catatan Pemeriksaan</label>
              <textarea
                class="form-control"
                v-model="form.catatan_pemeriksaan"
                :class="{ 'is-invalid': form.errors.has(`catatan_pemeriksaan`) }"
              ></textarea>
              <has-error :form="form" field="catatan_pemeriksaan" />
            </div>

            <div class="form-group">
              <button
                @click="submit()"
                :disabled="loading || form.kesimpulan_pemeriksaan == 'inkonklusif'"
                :class="{'btn-loading': loading}"
                class="btn btn-md btn-primary block full-width m-b"
                type="button"
              >
                <i class="fa fa-check"></i>
                Verifikasi Hasil PCR
              </button>
              <button
                @click="submit()"
                :disabled="loading || form.kesimpulan_pemeriksaan != 'inkonklusif'"
                :class="{'btn-loading': loading}"
                class="btn btn-md btn-warning block full-width m-b"
                type="button"
              >
                <i class="fa fa-refresh"></i>
                Periksa Ulang
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
import Dropzone from "nuxt-dropzone";
import "nuxt-dropzone/dropzone.css";

export default {
  middleware: "auth",
  components: {
    Dropzone
  },
  data() {
    return {
      loading: false
    };
  },
  async asyncData({ route, store }) {
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
    let default_hasil_deteksi = data.pcr.nama_kit_pemeriksaan == 'Liferiver' ? [{target_gen: 'ORF1AB'}, {target_gen: 'N'}, {target_gen: 'E'}, {target_gen: 'IC'}]
    : (data.pcr.nama_kit_pemeriksaan == 'Alplex' ? [{target_gen: 'RrDP'}, {target_gen: 'N'}, {target_gen: 'E'}, {target_gen: 'IC'}] 
    : [{}])
    let form = new Form({
      tanggal_input_hasil: new Date(),
      jam_input_hasil: ("" + new Date().getHours()).padStart(2, "0")+ ":" + ("" + new Date().getMinutes()).padStart(2, "0"),
      catatan_penerimaan: data.pcr.catatan_penerimaan,
      kesimpulan_pemeriksaan: data.pcr.kesimpulan_pemeriksaan,
      hasil_deteksi: data.pcr.hasil_deteksi ? data.pcr.hasil_deteksi : default_hasil_deteksi,
      grafik: data.pcr.grafik ? data.pcr.grafik : [],
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
        tanggalsampel: new Date(),
        pukulsampel: new Date().getHours() * 100 + new Date().getMinutes()
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