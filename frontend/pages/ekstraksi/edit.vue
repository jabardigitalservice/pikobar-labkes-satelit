<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Ubah Penerimaan dan Ekstraksi RNA</portal>
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
          <Ibox title="Penerimaan Ekstraksi">
            <div class="form-group">
              <label>
                Tanggal penerimaan sampel
                <span style="color:red">*</span>
              </label>
              <date-picker
                placeholder="Pilih Tanggal"
                format="d MMMM yyyy"
                input-class="form-control"
                :monday-first="true"
                :wrapper-class="{ 'is-invalid': form.errors.has(`tanggal_penerimaan_sampel`) }"
                v-model="form.tanggal_penerimaan_sampel"
              />
              <has-error :form="form" field="tanggal_penerimaan_sampel" />
            </div>

            <div class="form-group">
              <label>
                Jam penerimaan sampel
                <span style="color:red">*</span>
              </label>
              <input
                class="form-control"
                type="text"
                v-model="form.jam_penerimaan_sampel"
                v-mask="'##\:##'"
                :class="{ 'is-invalid': form.errors.has(`jam_penerimaan_sampel`) }"
              />
              <has-error :form="form" field="jam_penerimaan_sampel" />
            </div>

            <div class="form-group">
              <label>
                Petugas penerima sampel
                <span style="color:red">*</span>
              </label>
              <input
                class="form-control"
                type="text"
                v-model="form.petugas_penerima_sampel"
                placeholder="Petugas penerima sampel"
                :class="{ 'is-invalid': form.errors.has(`petugas_penerima_sampel`) }"
              />
              <has-error :form="form" field="petugas_penerima_sampel" />
            </div>

            <div class="form-group">
              <label>Catatan Penerimaan</label>
              <textarea
                class="form-control"
                v-model="form.catatan_penerimaan"
                :class="{ 'is-invalid': form.errors.has(`catatan_penerimaan`) }"
              ></textarea>
              <has-error :form="form" field="catatan_penerimaan" />
            </div>

            <div class="form-group">
              <label>
                Operator ekstraksi
                <span style="color:red">*</span>
              </label>
              <input
                class="form-control"
                type="text"
                v-model="form.operator_ekstraksi"
                placeholder="Operator ekstraksi"
                :class="{ 'is-invalid': form.errors.has(`operator_ekstraksi`) }"
              />
              <has-error :form="form" field="operator_ekstraksi" />
            </div>

            <div class="form-group">
              <label>
                Tanggal mulai ekstraksi
                <span style="color:red">*</span>
              </label>
              <date-picker
                placeholder="Pilih Tanggal"
                format="d MMMM yyyy"
                input-class="form-control"
                :monday-first="true"
                :wrapper-class="{ 'is-invalid': form.errors.has(`tanggal_mulai_ekstraksi`) }"
                v-model="form.tanggal_mulai_ekstraksi"
              />
              <has-error :form="form" field="tanggal_mulai_ekstraksi" />
            </div>

            <div class="form-group">
              <label>
                Jam mulai ekstraksi
                <span style="color:red">*</span>
              </label>
              <input
                class="form-control"
                type="text"
                v-model="form.jam_mulai_ekstraksi"
                v-mask="'##\:##'"
                :class="{ 'is-invalid': form.errors.has(`jam_mulai_ekstraksi`) }"
              />
              <has-error :form="form" field="jam_mulai_ekstraksi" />
            </div>

            <div class="form-group">
              <label>
                Metode ekstraksi
                <span style="color:red">*</span>
              </label>
              <dynamic-input :form="form" field="metode_ekstraksi" 
                :options="['Manual','Otomatis']" 
                :hasLainnya="true"
                ref="alat_ekstraksi_input"
                placeholder="Masukkan metode ekstraksi">
              </dynamic-input>
            </div>

            <div class="form-group">
              <label>
                Nama kit ekstraksi
                <span style="color:red">*</span>
              </label>
              <dynamic-input :form="form" field="nama_kit_ekstraksi" 
                :options="['Geneaid','Qiagen','Invitrogen','Roche','Addbio']" 
                :hasLainnya="true"
                ref="nama_kit_ekstraksi_input"
                placeholder="Masukkan nama kit ekstraksi">
              </dynamic-input>
            </div>

            <div class="form-group" v-show="form.metode_ekstraksi == 'Otomatis'">
              <label>
                Alat Ekstraksi
                <span style="color:red">*</span>
              </label>
              <dynamic-input :form="form" field="alat_ekstraksi" 
                :options="['Kingfisher','Genolution']"
                :hasLainnya="true"
                ref="alat_ekstraksi_input"
                placeholder="Masukkan alat ekstraksi">
              </dynamic-input>
            </div>
          </Ibox>
        </div>
        <div class="col-md-6">
          <Ibox title="Pengiriman Ekstraksi">
            <div class="form-group">
              <label>Nomor Sampel</label>
              <p class="form-control">
                <b>{{ data.nomor_sampel }}</b>
              </p>
            </div>

            <template v-if="can_edit_pengiriman">
              <div class="form-group" v-if="can_edit_pengiriman_tujuan">
                <label>
                  Dikirim ke Lab
                  <span style="color:red">*</span>
                </label>
                <div :class="{ 'is-invalid': form.errors.has('lab_pcr_id') }">
                  <div v-for="item in lab_pcr" :key="item.id">
                    <label class="form-check-label">
                      <input type="radio" v-model="form.lab_pcr_id" :value="item.id" />
                      {{item.text}}
                    </label>
                  </div>
                </div>
                <has-error :form="form" field="lab_pcr_id" />
              </div>

              <div class="form-group" v-if="form.lab_pcr_id =='999999'">
                <label>
                  Nama Lab
                  <span style="color:red">*</span>
                </label>
                <input
                  class="form-control"
                  type="text"
                  v-model="form.lab_pcr_nama"
                  :class="{ 'is-invalid': form.errors.has(`lab_pcr_nama`) }"
                />
                <has-error :form="form" field="lab_pcr_nama" />
              </div>

              <div class="form-group">
                <label>
                  Tanggal pengiriman RNA
                  <span style="color:red">*</span>
                </label>
                <date-picker
                  placeholder="Pilih Tanggal"
                  format="d MMMM yyyy"
                  input-class="form-control"
                  :monday-first="true"
                  :wrapper-class="{ 'is-invalid': form.errors.has(`tanggal_pengiriman_rna`) }"
                  v-model="form.tanggal_pengiriman_rna"
                />
                <has-error :form="form" field="tanggal_pengiriman_rna" />
              </div>

              <div class="form-group">
                <label>
                  Jam pengiriman RNA
                  <span style="color:red">*</span>
                </label>
                <input
                  class="form-control"
                  type="text"
                  v-model="form.jam_pengiriman_rna"
                  v-mask="'##\:##'"
                  :class="{ 'is-invalid': form.errors.has(`jam_pengiriman_rna`) }"
                />
                <has-error :form="form" field="jam_pengiriman_rna" />
              </div>

              <div class="form-group">
                <label>
                  Nama pengirim RNA
                  <span style="color:red">*</span>
                </label>
                <input
                  class="form-control"
                  type="text"
                  v-model="form.nama_pengirim_rna"
                  placeholder="Nama pengirim RNA"
                  :class="{ 'is-invalid': form.errors.has(`nama_pengirim_rna`) }"
                />
                <has-error :form="form" field="nama_pengirim_rna" />
              </div>

              <div class="form-group">
                <label>Catatan Pengiriman</label>
                <textarea
                  class="form-control"
                  v-model="form.catatan_pengiriman"
                  placeholder=""
                  :class="{ 'is-invalid': form.errors.has(`catatan_pengiriman`) }"
                ></textarea>
                <has-error :form="form" field="catatan_pengiriman" />
              </div>
            </template>

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
    let resp = await axios.get("/v1/ekstraksi/detail/" + route.params.id);
    let data = resp.data.data;
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
      can_edit_pengiriman: can_edit_pengiriman,
      can_edit_pengiriman_tujuan: can_edit_pengiriman_tujuan,
      form: new Form({
        tanggal_penerimaan_sampel: data.ekstraksi.tanggal_penerimaan_sampel,
        jam_penerimaan_sampel: data.ekstraksi.jam_penerimaan_sampel,
        petugas_penerima_sampel: data.ekstraksi.petugas_penerima_sampel,
        operator_ekstraksi: data.ekstraksi.operator_ekstraksi,
        tanggal_mulai_ekstraksi: data.ekstraksi.tanggal_mulai_ekstraksi,
        jam_mulai_ekstraksi: data.ekstraksi.jam_mulai_ekstraksi,
        metode_ekstraksi: data.ekstraksi.metode_ekstraksi,
        nama_kit_ekstraksi: data.ekstraksi.nama_kit_ekstraksi,
        alat_ekstraksi: data.ekstraksi.alat_ekstraksi,
        lab_pcr_id: data.lab_pcr_id,
        lab_pcr_nama: data.lab_pcr_nama,
        tanggal_pengiriman_rna: data.ekstraksi.tanggal_pengiriman_rna,
        jam_pengiriman_rna: data.ekstraksi.jam_pengiriman_rna,
        nama_pengirim_rna: data.ekstraksi.nama_pengirim_rna,
        catatan_penerimaan: data.ekstraksi.catatan_penerimaan,
        catatan_pengiriman: data.ekstraksi.catatan_pengiriman,
      }),
      loading: false
    };
  },
  watch: {
    'form.metode_ekstraksi': function(newval, oldval) {
      if (this.form.metode_ekstraksi != 'Otomatis') {
        this.form.alat_ekstraksi = ''
      }
    },
  },
  head() {
    return {
      title: "Ubah Data Sampel"
    };
  },
  methods: {
    dummy() {

    },
    async submit() {
      // Submit the form.
      try {
        this.loading = true;
        const response = await this.form.post("/v1/ekstraksi/edit/" + this.$route.params.id);
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