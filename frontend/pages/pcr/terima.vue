<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Penerimaan Sampel RNA Baru</portal>
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
            sampel-status="extraction_sample_sent"
            title="Daftar Sampel RNA yang Diterima"
          ></sample-picker>
        </div>
        <div class="col-md-6">
          <Ibox title="Informasi PCR">
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
                v-model="form.petugas_penerima_sampel_rna"
                placeholder="Petugas penerima sampel"
                :class="{ 'is-invalid': form.errors.has(`petugas_penerima_sampel_rna`) }"
              />
              <has-error :form="form" field="petugas_penerima_sampel_rna" />
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
                Operator PCR
                <span style="color:red">*</span>
              </label>
              <input
                class="form-control"
                type="text"
                v-model="form.operator_real_time_pcr"
                placeholder="Operator PCR"
                :class="{ 'is-invalid': form.errors.has(`operator_real_time_pcr`) }"
              />
              <has-error :form="form" field="operator_real_time_pcr" />
            </div>

            <div class="form-group">
              <label>
                Tanggal mulai PCR
                <span style="color:red">*</span>
              </label>
              <date-picker
                placeholder="Pilih Tanggal"
                format="d MMMM yyyy"
                input-class="form-control"
                :monday-first="true"
                :wrapper-class="{ 'is-invalid': form.errors.has(`tanggal_mulai_pemeriksaan`) }"
                v-model="form.tanggal_mulai_pemeriksaan"
              />
              <has-error :form="form" field="tanggal_mulai_pemeriksaan" />
            </div>

            <div class="form-group">
              <label>
                Jam mulai PCR
                <span style="color:red">*</span>
              </label>
              <input
                class="form-control"
                type="text"
                v-model="form.jam_mulai_pcr"
                v-mask="'##\:##'"
                :class="{ 'is-invalid': form.errors.has(`jam_mulai_pcr`) }"
              />
              <has-error :form="form" field="jam_mulai_pcr" />
            </div>

            <div class="form-group">
              <label>
                Jam selesai PCR
                <span style="color:red">*</span>
              </label>
              <input
                class="form-control"
                type="text"
                v-model="form.jam_selesai_pcr"
                v-mask="'##\:##'"
                :class="{ 'is-invalid': form.errors.has(`jam_selesai_pcr`) }"
              />
              <has-error :form="form" field="jam_selesai_pcr" />
            </div>

            <div class="form-group">
              <label>
                Metode pemeriksaan PCR
                <span style="color:red">*</span>
              </label>
              <input
                class="form-control"
                type="text"
                v-model="form.metode_pemeriksaan"
                placeholder="Metode PCR"
                :class="{ 'is-invalid': form.errors.has(`metode_pemeriksaan`) }"
              />
              <has-error :form="form" field="metode_pemeriksaan" />
            </div>

            <div class="form-group">
              <label>
                Nama kit pemeriksaan PCR
                <span style="color:red">*</span>
              </label>
              <input
                class="form-control"
                type="text"
                v-model="form.nama_kit_pemeriksaan"
                placeholder="Nama kit PCR"
                :class="{ 'is-invalid': form.errors.has(`nama_kit_pemeriksaan`) }"
              />
              <has-error :form="form" field="nama_kit_pemeriksaan" />
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
    return {
      form: new Form({
        tanggal_penerimaan_sampel: new Date(),
        jam_penerimaan_sampel: this.getTimeNow(),
        petugas_penerima_sampel_rna: "",
        catatan_penerimaan: "",
        operator_real_time_pcr: "",
        tanggal_mulai_pemeriksaan: new Date(),
        jam_mulai_pcr: this.getTimeNow(),
        jam_selesai_pcr: this.getTimeNow(),
        metode_pemeriksaan: "",
        nama_kit_pemeriksaan: "",
        samples: []
      }),
      loading: false,
      input_nomor_sampel: ""
    };
  },
  head() {
    return {
      title: "Penerimaan Sampel RNA"
    };
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
        petugas_penerima_sampel_rna: "",
        catatan_penerimaan: "",
        operator_real_time_pcr: "",
        tanggal_mulai_pemeriksaan: new Date(),
        jam_mulai_pcr: this.getTimeNow(),
        jam_selesai_pcr: this.getTimeNow(),
        metode_pemeriksaan: "",
        nama_kit_pemeriksaan: "",
        samples: []
      });
      this.input_nomor_sampel = "";
    },
    async submit() {
      // Submit the form.
      try {
        if (!this.$refs.sample_picker.isFormValid()) {
          return
        }
        this.loading = true
        const response = await this.form.post("/v1/pcr/terima");
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