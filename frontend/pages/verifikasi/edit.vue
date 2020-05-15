<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Verifikasi Hasil Pemeriksaan</portal>
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
              <label>Tanggal Input Hasil</label>
              <p class="form-control">
                <b>
                  {{ data.last_pemeriksaan_sampel.tanggal_input_hasil }}
                </b>
              </p>
            </div>

            <div class="form-group">
              <label>Jam Input Hasil</label>
              <p class="form-control">
                <b>
                  {{ data.last_pemeriksaan_sampel.jam_input_hasil }}
                </b>
              </p>
            </div>

            <div class="form-group">
              <label>Nama Pasien</label>
              <p class="form-control">
                <b>{{ data.pasien.nama_lengkap }}</b>
              </p>
            </div>

            <div class="form-group">
              <label>NIK</label>
              <p class="form-control">
                <b>{{ data.pasien.nik }}</b>
              </p>
            </div>

            <div class="form-group">
              <label>Tanggal Lahir</label>
              <p class="form-control">
                <b>{{ data.pasien.tanggal_lahir | formatDate }}</b>
              </p>
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
                </tr>
              </thead>
              <tbody class="field_wrapper">
                <tr v-for="(hasil, $index) in form.hasil_deteksi" :key="$index">
                  <td>
                    <p class="form-control">
                      <b>
                        {{ hasil.target_gen }}
                      </b>
                    </p>
                  </td>
                  <td>
                    <p class="form-control">
                      <b v-if="!!hasil.ct_value">{{ hasil.ct_value }}</b>
											<b v-if="hasil.ct_value == null">{{ '-' }}</b>
                    </p>
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
                  <label class="fancy-radio custom-color-green m-0 w-100">
                      <input type="radio" v-model="form.kesimpulan_pemeriksaan" value="positif" >
                      <span><i></i>POSITIF</span>
                  </label>
                  <!-- <label class="form-check-label">
                    <input type="radio" v-model="form.kesimpulan_pemeriksaan" value="positif" />
                    <b>POSITIF</b>
                  </label> -->
                </div>
                <div>
                  <label class="fancy-radio custom-color-green m-0 w-100">
                      <input type="radio" v-model="form.kesimpulan_pemeriksaan" value="negatif"  >
                      <span><i></i>NEGATIF</span>
                  </label>
                  <!-- <label class="form-check-label">
                    <input type="radio" v-model="form.kesimpulan_pemeriksaan" value="negatif" />
                    <b>NEGATIF</b>
                  </label> -->
                </div>
                <div>
                  <label class="fancy-radio custom-color-green m-0 w-100">
                      <input type="radio" v-model="form.kesimpulan_pemeriksaan" value="sampel kurang" >
                      <span><i></i>SAMPEL KURANG</span>
                  </label>
                  <!-- <label class="form-check-label">
                    <input type="radio" v-model="form.kesimpulan_pemeriksaan" value="sampel kurang" />
                    <b>SAMPEL KURANG</b>
                  </label> -->
                </div>
              </div>
              <has-error :form="form" field="kesimpulan_pemeriksaan" />
            </div>

            <div class="form-group">
              <label>
                Grafik
              </label>
              <div :class="{ 'is-invalid': form.errors.has('grafik') }">
                <a :href="url" target="_blank" v-for="(url, $index) in data.pemeriksaan_sampel.grafik" :key="$index" class="thumbnail-wrapper">
                  <img :src="url" />
                </a>
              </div>
              <has-error :form="form" field="grafik" />
            </div>

            <input type="hidden" v-model="form.last_pemeriksaan_id">

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
                :disabled="loading"
                :class="{'btn-loading': loading}"
                class="btn btn-md btn-primary block full-width m-b"
                type="button"
              >
                <i class="fa fa-check"></i>
                {{ 'Verifikasi' }}
                <!-- {{ form.kesimpulan_pemeriksaan != 'inkonklusif' ? 'Verifikasi' : 'Periksa Ulang'}} -->
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
    let resp = await axios.get("/v1/verifikasi/detail/" + route.params.id);
    let data = resp.data.data;

    if (!data.pasien) {
      data.pasien = {};
    }

    let form = new Form({
      tanggal_input_hasil: new Date(),
      jam_input_hasil: ("" + new Date().getHours()).padStart(2, "0")+ ":" + ("" + new Date().getMinutes()).padStart(2, "0"),
      last_pemeriksaan_id: data.last_pemeriksaan_sampel.id,
      catatan_pemeriksaan: data.last_pemeriksaan_sampel.catatan_pemeriksaan,
      kesimpulan_pemeriksaan: data.last_pemeriksaan_sampel.kesimpulan_pemeriksaan,
      hasil_deteksi: data.last_pemeriksaan_sampel.hasil_deteksi ? data.last_pemeriksaan_sampel.hasil_deteksi : [{}],
      grafik: data.last_pemeriksaan_sampel.grafik ? data.last_pemeriksaan_sampel.grafik : [],
    });

    return {
      data,
      form,
    };
  },
  head() {
    return {
      title: "Verifikasi hasil pemeriksaan"
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
        const response = await this.form.post("/v1/verifikasi/edit-status-sampel/" + this.$route.params.id);
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