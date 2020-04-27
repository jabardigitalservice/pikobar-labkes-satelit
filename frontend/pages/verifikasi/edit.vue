<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Ubah Status Hasil Pemeriksaan</portal>
    <portal to="title-action">
      <div class="title-action">
        <a href="#" @click.prevent="$router.back()" class="btn btn-default">
          <i class="uil-arrow-left"></i>Kembali
        </a>
      </div>
    </portal>
    <div class="error-container" v-if="error">
      <div class="error">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="90"
          height="90"
          fill="#DBE1EC"
          viewBox="0 0 48 48"
        >
          <path
            d="M22 30h4v4h-4zm0-16h4v12h-4zm1.99-10C12.94 4 4 12.95 4 24s8.94 20 19.99 20S44 35.05 44 24 35.04 4 23.99 4zM24 40c-8.84 0-16-7.16-16-16S15.16 8 24 8s16 7.16 16 16-7.16 16-16 16z"
          />
        </svg>
        <div class="title">Sampel ini tidak bisa diubah karena masih pada status {{ data.status.deskripsi }}</div>
        <p class="description">
          <a @click.prevent="$router.back()" class="error-link">Kembali</a>
        </p>
      </div>
    </div>
    <form @submit.prevent="dummy" v-if="!error">
      <div class="row">
        <div class="col-md-6">
          <Ibox title="Info Pasien">         


            <div class="form-group">
              <label>Nomor Register</label>
              <p class="form-control">
                <b>{{ data.nomor_register }}</b>
              </p>
            </div>

            <div class="form-group">
              <label>Nama Pasien</label>
              <p class="form-control">
                <b>{{ data.pasien.nama_depan }} {{ data.pasien.nama_belakang }}</b>
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

          <Ibox title="Hasil Pemeriksaan Sampel">
            
            <div class="form-group">
              <label>Nomor Sampel</label>
              <p class="form-control">
                <b>{{ data.nomor_sampel }}</b>
              </p>
            </div>


            <div class="form-group">
              <label>Tanggal input hasil</label>
              <p class="form-control">
                <b v-if="data.pemeriksaan_sampel && data.pemeriksaan_sampel.tanggal_input_hasil">
                  {{data.pemeriksaan_sampel.tanggal_input_hasil | formatDate}} pada {{data.pemeriksaan_sampel.jam_input_hasil}}
                </b>
              </p>
            </div>

            <div class="form-group">
              <label>Petugas Penerima Sampel RNA</label>
              <p class="form-control">
                <b v-if="data.pemeriksaan_sampel && data.pemeriksaan_sampel.petugas_penerima_sampel_rna">
                  {{data.pemeriksaan_sampel.petugas_penerima_sampel_rna }}
                </b>
              </p>
            </div>

            <div class="form-group">
              <label>Kesimpulan Hasil</label>
              <p class="form-control">
                <b v-if="data.pemeriksaan_sampel && data.pemeriksaan_sampel.kesimpulan_pemeriksaan">
                  {{data.pemeriksaan_sampel.kesimpulan_pemeriksaan }}
                </b>
              </p>
            </div>

            <div class="form-group">
              <label>Catatan pemeriksaan</label>
              <p class="form-control">
                <b v-if="data.pemeriksaan_sampel && data.pemeriksaan_sampel.catatan_pemeriksaan">
                  {{data.pemeriksaan_sampel.catatan_pemeriksaan }}
                </b>
              </p>
            </div>

            <div class="form-group">
              <label for="">Status Sampel</label>
              <select 
                class="form-control" 
                v-model="form.sampel_status" 
                :class="{ 'is-invalid': form.errors.has(`sampel_status`) }"
              >
                <option 
                  v-for="sampelStatus in sampelSetatusList" 
                  v-bind:value="sampelStatus.status_sampel"
                  v-bind:key="sampelStatus.status_sampel"
                >
                  {{ sampelStatus.deskripsi }}
                </option>
              </select>
              <has-error :form="form" field="sampel_status" />

            </div>

            <div class="form-group">
              <button
                @click="submit()"
                :disabled="loading"
                :class="{'btn-loading': loading}"
                class="btn btn-md btn-primary block full-width m-b"
                type="button"
              >
                <i class="fa fa-check"></i> Simpan Perubahan
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
import { mapGetters } from "vuex";

export default {
  middleware: "auth",
  computed: mapGetters({
    user: "auth/user",
    lab_pcr: "options/lab_pcr",
  }),
  async asyncData({ route, store }) {
    let error = false;
    let can_edit_pengiriman = true;
    let can_edit_pengiriman_tujuan = true;
    let resp = await axios.get("/v1/verifikasi/detail/" + route.params.id);
    let data = resp.data.data;

    let sampelStatusCall = await axios.get("/v1/verifikasi/get-sampel-status");
    let sampelSetatusList = sampelStatusCall.data.data;  
    // sampel_status  

    if (!data.ekstraksi) {
      data.ekstraksi = {};
    }

    if (["waiting_sample", "sample_taken", "sample_verified", "sample_valid"].indexOf(data.sampel_status) > -1) {
      error = true;
    }

    if (["waiting_sample", "sample_taken", "extraction_sample_extracted", "sample_verified", "sample_valid"].indexOf(data.sampel_status) > -1) {
      can_edit_pengiriman = false;
    }

    if (["pcr_sample_received", "pcr_sample_analyzed"].indexOf(data.sampel_status) > -1) {
      can_edit_pengiriman_tujuan = false;
    }

    if (!store.getters['options/lab_pcr'].length) {
      await store.dispatch('options/fetchLabPCR')
    }

    return {
      error: error,
      data: data,
      sampelSetatusList: sampelSetatusList,
      can_edit_pengiriman: can_edit_pengiriman,
      can_edit_pengiriman_tujuan: can_edit_pengiriman_tujuan,
      form: new Form({
        sampel_status: data.status_sampel,
      }),
      loading: false
    };

  },

  head() {
    return {
      title: "Ubah status hasil pemeriksaan"
    };
  },

  methods: {
    dummy() {

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
      } catch (err) {
        console.log(err)
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
  }
};
</script>

<style scoped>
.error-container {
  padding: 1rem;
  background: #f7f8fb;
  color: #47494e;
  text-align: center;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  font-family: sans-serif;
  font-weight: 100 !important;
  -ms-text-size-adjust: 100%;
  -webkit-text-size-adjust: 100%;
  -webkit-font-smoothing: antialiased;
}
.error {
  max-width: 450px;
}
.title {
  font-size: 1.5rem;
  margin-top: 15px;
  color: #47494e;
  margin-bottom: 8px;
}
.description {
  color: #7f828b;
  line-height: 21px;
  margin-bottom: 10px;
}
.error-container a {
  color: #7f828b !important;
  text-decoration: none;
}
.logo {
  position: fixed;
  left: 12px;
  bottom: 12px;
}
</style>