<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Penerimaan dan Ekstraksi RNA Baru</portal>
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
          <sample-picker v-model="form.samples"
            ref="sample_picker"
            sampel-status="sample_taken"
            title="Daftar Sampel yang Diterima" 
            :selectedNomorSampels="selectedNomorSampels"
          ></sample-picker>
        </div>
        <div class="col-md-6">
          <Ibox title="Informasi Ekstraksi">
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
              <label>
                Catatan Penerimaan
              </label>
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
                ref="metode_ekstraksi_input"
                placeholder="Masukkan metode ekstraksi">
              </dynamic-input>
            </div>

            <div class="form-group" v-show="form.metode_ekstraksi != 'Otomatis'">
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

            <div class="form-group">
              <button
                @click="submit()"
                :disabled="loading"
                :class="{'btn-loading': loading}"
                class="btn btn-md btn-primary block full-width m-b"
                type="button"
              >
                <i class="fa fa-check"></i> Terima Sampel
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
import SamplePicker from '~/components/SamplePicker'

export default {
  middleware: "auth",
  components: {
    SamplePicker,
  },
  data() {
		let selectedNomorSampels = this.$store.state.ekstraksi_penerimaan.selectedSampels;

    return {
      form: new Form({
        tanggal_penerimaan_sampel: new Date(),
        jam_penerimaan_sampel: this.getTimeNow(),
        petugas_penerima_sampel: "",
        catatan_penerimaan: "",
        operator_ekstraksi: "",
        tanggal_mulai_ekstraksi: new Date(),
        jam_mulai_ekstraksi: this.getTimeNow(),
        metode_ekstraksi: "",
        nama_kit_ekstraksi: "",
        alat_ekstraksi: "",
        samples: []
      }),
      selectedNomorSampels,
      loading: false,
    };
  },
  head() {
    return {
      title: "Penerimaan Sampel"
    };
  },
  watch: {
    'form.metode_ekstraksi': function(newval, oldval) {
      if (this.form.metode_ekstraksi == 'Otomatis') {
        this.form.nama_kit_ekstraksi = ''
      }
      if (this.form.metode_ekstraksi != 'Otomatis') {
        this.form.alat_ekstraksi = ''
      }
    },
  },
  methods: {
    getTimeNow() {
      let h = ('' + new Date().getHours()).padStart(2,'0')
      return h + ':' + (''+new Date().getMinutes()).padStart(2,'0')
    },
    dummy() {
      return false;
    },
    initForm() {
      this.form = new Form({
        tanggal_penerimaan_sampel: new Date(),
        jam_penerimaan_sampel: this.getTimeNow(),
        petugas_penerima_sampel: "",
        catatan_penerimaan: "",
        operator_ekstraksi: "",
        tanggal_mulai_ekstraksi: new Date(),
        jam_mulai_ekstraksi: this.getTimeNow(),
        metode_ekstraksi: "",
        nama_kit_ekstraksi: "",
        samples: []
      });
      this.$refs.metode_ekstraksi_input.reset()
      this.$refs.nama_kit_ekstraksi_input.reset()
      this.$refs.alat_ekstraksi_input.reset()
    },
    async submit() {
      // Submit the form.
      try {
        if (!this.$refs.sample_picker.isFormValid()) {
          return
        }
        this.loading = true
        const response = await this.form.post("/v1/ekstraksi/terima");

        // Clear selected sampels
        this.$store.commit('ekstraksi_penerimaan/clear');

        this.$toast.success(response.data.message, {
          icon: "check",
          iconPack: "fontawesome",
          duration: 5000
        });
        this.initForm();
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
          console.log(err)
          this.$swal.fire(
            "Terjadi kesalahan",
            "Silakan hubungi Admin",
            "error"
          );
        }
      }
      this.loading = false
    },
  }
};
</script>