<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Validasi Hasil Pemeriksaan</portal>
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

            <table class="table">
              <tbody>
                <tr>
                  <td align="right">
                    <b>Nomor Registrasi</b>
                  </td>
                  <td>
                    {{ data.nomor_register }}
                  </td>
                </tr>
                <tr>
                  <td align="right">
                    <b>Nomor Induk Kependudukan</b>
                  </td>
                  <td>
                    {{ data.pasien.no_ktp }}
                  </td>
                </tr>
                <tr>
                  <td align="right">
                    <b>Nama Pasien</b>
                  </td>
                  <td>
                    {{ data.pasien.nama_depan }} {{ data.pasien.nama_belakang }}
                  </td>
                </tr>
                <tr>
                  <td align="right">
                    <b>Tanggal Lahir Pasien</b>
                  </td>
                  <td>
                    {{ data.pasien.tanggal_lahir | formatDate }}
                  </td>
                </tr>
                <tr>
                    <td width="30%" align="right">
                      <b>No. Telp/HP</b>
                    </td>
                    <td width="60%">
                      <span v-if="data.pasien.no_hp">{{data.pasien.no_hp }}</span>
                      <span v-else-if="data.pasien.no_telp">{{data.pasien.no_telp }}</span>
                    </td>
                  </tr>
                  <tr>
                    <td width="30%" align="right">
                      <b>Alamat</b>
                    </td>
                    <td width="60%">
                      <span v-if="data.pasien.alamat">{{data.pasien.alamat }}</span>
                    </td>
                  </tr>
                <tr>
                  <td align="right">
                    <b>Nomor Sampel</b>
                  </td>
                  <td>
                    {{ data.nomor_sampel }}
                  </td>
                </tr>
                <tr>
                  <td align="right">
                    <b>Jenis Sampel</b>
                  </td>
                  <td>
                    {{ data.jenis_sampel }}
                  </td>
                </tr>
                <tr>
                  <td align="right">
                    <b>Tanggal Penerimaan Sampel</b>
                  </td>
                  <td>
                    {{ data.last_pemeriksaan_sampel.tanggal_penerimaan_sampel | formatDate }}
                  </td>
                </tr>
                <tr>
                  <td align="right">
                    <b>Petugas Penerimaan Sampel RNA</b>
                  </td>
                  <td>
                    {{ data.last_pemeriksaan_sampel.petugas_penerima_sampel_rna }}
                  </td>
                </tr>
                <tr>
                  <td align="right">
                    <b>Lab Penerima</b>
                  </td>
                  <td>
                    {{ data.lab_pcr_nama }}
                  </td>
                </tr>
                <tr>
                  <td align="right">
                    <b>Operator Realtime PCR</b>
                  </td>
                  <td>
                    {{ data.last_pemeriksaan_sampel.operator_real_time_pcr }}
                  </td>
                </tr>
                <tr>
                  <td align="right">
                    <b>Tanggal Mulai Pemeriksaan</b>
                  </td>
                  <td>
                    {{ data.last_pemeriksaan_sampel.tanggal_mulai_pemeriksaan | formatDate }}
                  </td>
                </tr>
                <tr>
                  <td align="right">
                    <b>Jam Mulai Pemeriksaan</b>
                  </td>
                  <td>
                    {{ data.last_pemeriksaan_sampel.jam_mulai_pcr }}
                  </td>
                  
                </tr>
                <tr>
                  <td align="right">
                    <b>Jam Selesai Pemeriksaan</b>
                  </td>
                  <td>
                    {{ data.last_pemeriksaan_sampel.jam_selesai_pcr }}
                  </td>
                </tr>
                <tr>
                  <td align="right">
                    <b>Metode Pemeriksaan</b>
                  </td>
                  <td>
                    {{ data.last_pemeriksaan_sampel.metode_pemeriksaan }}
                  </td>
                </tr>
                <tr>
                  <td align="right">
                    <b>Nama Kit Pemeriksaan</b>
                  </td>
                  <td>
                    {{ data.last_pemeriksaan_sampel.nama_kit_pemeriksaan }}
                  </td>
                </tr>
              </tbody>
            </table>

          </Ibox>
        </div>

        <div class="col-md-6">
          <Ibox title="Hasil Analisa PCR">
            <table class="table dt-responsive table-bordered" style="width:100%">
              <thead>
                <tr>
                  <th>Target Gen</th>
                  <th>CT Value</th>
                </tr>
              </thead>
              <tbody class="field_wrapper">
                <tr v-for="(hasil, $index) in form.hasil_deteksi" :key="$index">
                  <td>
                    <p class="form-control control-disabled">
                      <b>
                        {{ hasil.target_gen }}
                      </b>
                    </p>
                  </td>
                  <td>
                    <p class="form-control control-disabled">
                      <b>
                        {{ hasil.ct_value }}
                      </b>
                    </p>
                  </td>
                </tr>
              </tbody>
            </table>

            <div class="form-group">
              <label>
                Kesimpulan Pemeriksaan
              </label>
              <div>
                <p class="form-control control-disabled">
                    <b>{{ form.kesimpulan_pemeriksaan }}</b>
                  </p>
              </div>
            </div>

            <div class="form-group">
              <label>
                Grafik
              </label>
              <div>
                <a :href="url" target="_blank" v-for="(url, $index) in data.pemeriksaan_sampel.grafik" :key="$index" class="thumbnail-wrapper">
                  <img :src="url" />
                </a>
              </div>
            </div>

            <input type="hidden" v-model="form.last_pemeriksaan_id">

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
                {{ 'Validasi' }}
                <!-- {{ form.kesimpulan_pemeriksaan != 'inkonklusif' ? 'Validasi' : 'Periksa Ulang'}} -->
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
    let resp = await axios.get("/v1/validasi/detail/" + route.params.id);
    let data = resp.data.data;

    let respListValidator = await axios.get("/v1/validasi/list-validator");
    let listValidator = respListValidator.data.data;

    if (!data.pasien) {
      data.pasien = {};
    }

    let form = new Form({
      validator: data.validator_id,
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
      listValidator
    };
  },
  head() {
    return {
      title: "Validasi hasil pemeriksaan"
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
        const response = await this.form.post("/v1/validasi/edit-status-sampel/" + this.$route.params.id);
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
    }
  }
};
</script>


<style scoped>
  .control-disabled {
    background-color: #eaeaea;
  }
</style>