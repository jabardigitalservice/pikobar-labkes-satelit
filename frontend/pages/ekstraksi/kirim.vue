<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Pengiriman Sampel Ekstraksi RNA ke Lab</portal>
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
            sampel-status="extraction_sample_extracted"
            title="Daftar Sampel yang Akan Dikirim" 
            :selectedNomorSampels="selectedNomorSampels"
          ></sample-picker>
        </div>
        <div class="col-md-6">
          <Ibox title="Informasi Ekstraksi">
            <div class="form-group">
              <label>
                Dikirim ke Lab
                <span style="color:red">*</span>
              </label>
              <div :class="{ 'is-invalid': form.errors.has('lab_pcr_id') }">
                <div v-for="item in lab_pcr" :key="item.id">
                  <label class="fancy-radio custom-color-green m-0 w-100">
                      <input type="radio" v-model="form.lab_pcr_id" :value="item.id" >
                      <span><i></i>{{item.text}}</span>
                  </label>
                  <!-- <label class="form-check-label">
                    <input type="radio" v-model="form.lab_pcr_id" :value="item.id" />
                    {{item.text}}
                  </label> -->
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
              <label>
                Catatan Pengiriman
              </label>
              <textarea
                class="form-control"
                v-model="form.catatan_pengiriman"
                :class="{ 'is-invalid': form.errors.has(`catatan_pengiriman`) }"
              ></textarea>
              <has-error :form="form" field="catatan_pengiriman" />
            </div>

            <div class="form-group">
              <button
                @click="submit()"
                :disabled="loading"
                :class="{'btn-loading': loading}"
                class="btn btn-md btn-primary block full-width m-b"
                type="button"
              >
                <i class="fa fa-check"></i> Kirim Sampel
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
import { mapGetters } from "vuex";

export default {
  middleware: "auth",
  components: {
    SamplePicker,
  },
  computed: mapGetters({
    user: "auth/user",
    lab_pcr: "options/lab_pcr",
  }),
  data() {

		let selectedNomorSampels = this.$store.state.ekstraksi_dilakukan.selectedSampels;

    return {
      form: new Form({
        tanggal_pengiriman_rna: new Date(),
        jam_pengiriman_rna: this.getTimeNow(),
        nama_pengirim_rna: "",
        catatan_pengiriman: "",
        lab_pcr_id: "1",
        lab_pcr_nama: "",
        samples: []
      }),
      selectedNomorSampels,
      loading: false,
    };
  },
  async asyncData({store}) {
    if (!store.getters['options/lab_pcr'].length) {
      await store.dispatch('options/fetchLabPCR')
    }
    return {}
  },
  head() {
    return {
      title: "Pengiriman Sampel Ekstraksi"
    };
  },
  methods: {
    getTimeNow() {
      let h = ("" + new Date().getHours()).padStart(2, "0");
      return h + ":" + ("" + new Date().getMinutes()).padStart(2, "0");
    },
    dummy() {
      return false;
    },
    initForm() {
      this.form = new Form({
        tanggal_pengiriman_rna: new Date(),
        jam_pengiriman_rna: this.getTimeNow(),
        nama_pengirim_rna: "",
        catatan_pengiriman: "",
        tanggal_mulai_ekstraksi: new Date(),
        jam_mulai_ekstraksi: this.getTimeNow(),
        lab_pcr_id: this.form.lab_pcr_id,
        samples: []
      });
    },
    async submit() {
      // Submit the form.
      try {
        if (!this.$refs.sample_picker.isFormValid()) {
          return
        }
        this.loading = true;
        const response = await this.form.post("/v1/ekstraksi/kirim");

        // Clear selected sampels
        this.$store.commit('ekstraksi_dilakukan/clear');

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
      this.loading = false;
    },
  },
};
</script>