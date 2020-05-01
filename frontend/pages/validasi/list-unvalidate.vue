<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Menunggu Validasi</portal>

    <div class="row">
      <div class="col-lg-12">

        <Ibox title="Sampel Menunggu Verifikasi">
          <p class="sub-header">
            Berikut ini adalah daftar sampel dari hasil verifikasi sampel</p>

          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Filter Kesimpulan Pemeriksaan</label>
                <select class="form-control" v-model="params1.kesimpulan_pemeriksaan">
                  <option value="">Semua Hasil</option>
                  <option value="positif">Positif</option>
                  <option value="negatif">Negatif</option>
                  <option value="inkonklusif">Inkonklusif</option>

                </select>
              </div>
            </div>
            <div class="col-md-9">
              <!-- Button trigger modal -->
              <button type="button" class="btn btn-primary pull-right" 
                data-toggle="modal" data-target="#modalBulkValidate"
              >
                 Validasi
              </button>
            </div>
          </div>
          <ajax-table
            url="/v1/validasi/list"
            :oid="'validasi'"
            :params="params1"
            :config="{
                    autoload: true,
                    has_number: true,
                    has_entry_page: true,
                    has_pagination: true,
                    has_action: true,
                    has_search_input: true,
                    custom_header: '',
                    default_sort: '',
                    custom_empty_page: true,
                    class: {
                        table: [],
                        wrapper: ['table-responsive'],
                    }
                    }"
                :rowtemplate="'tr-validasi'"
                :columns="{
                      checkbox_input: '#',
                      nomor_register: 'Nomor Register',
                      pasien_nama : 'Nama Pasien',
                      kota_nama : 'Kota Domisili',
                      nomor_sampel : 'Nomor Sampel',
                      parameter_lab: 'Parameter Lab',
                      kesimpulan_pemeriksaan: 'Kesimpulan Pemeriksaan',
                    }"
          ></ajax-table>
        </Ibox>
      </div>
    </div>

    <custom-modal 
      modal_id="modalBulkValidate" 
      title="Validasi Sampel"
    >
      <div slot="body">

        <div class="alert alert-danger" v-show="sampelIds.length < 1">
          <strong>
            Perhatian!
          </strong>
            Belum ada sampel yang dipilih. Silahkan centang beberapa pada tabel.
        </div>

        <form @submit.prevent="dummy">
          <div class="row">
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
          </div>
          <div class="row">
            <button
                @click="submit()"
                :disabled="loading"
                :class="{'btn-loading': loading}"
                class="btn btn-md btn-primary block full-width m-b"
                type="button"
              >
                <i class="fa fa-check"></i>
                {{ 'Validasi' }}
              </button>
          </div>
        </form>
      </div>
    </custom-modal>

  </div>
</template>
 
<script>
import Form from "vform";
import axios from "axios";
import JQuery from "jquery";
import CustomModal from "~/components/CustomModal";

export default {
  middleware: "auth",
  components: {
    CustomModal
  },
  data() {
    return {
      params1: {
        kesimpulan_pemeriksaan: ""
      },
      loading: false
    };
  },
  async asyncData({ route, store }) {

    let respListValidator = await axios.get("/v1/validasi/list-validator");
    let listValidator = respListValidator.data.data;
    let sampelIds = store.state.validasi.selectedSampels;

    let form = new Form({
      validator: null,
      sampels: sampelIds
    });

    return {
      form,
      listValidator,
      sampelIds
    };
  },
  head() {
    return { title: "Sampel Hasil Pemeriksaan" };
  },
  watch: {
    'params1.kesimpulan_pemeriksaan': function(newVal, oldVal) {
      this.$bus.$emit('refresh-ajaxtable', 'validasi')
    },
  },
  methods: {
    dummy() {
      return false;
    },
    async submit() {
      // Submit the form.
      try {

        this.loading = true;

        const response = await this.form.post("/v1/validasi/bulk-validasi");

        this.$toast.success(response.data.message, {
          icon: "check",
          iconPack: "fontawesome",
          duration: 5000
        });

        // clear selected checkbox sampel
        this.$store.commit('validasi/clear');

        this.$bus.$emit('refresh-ajaxtable', 'validasi');

        JQuery('#modalBulkValidate').modal('hide');

        // this.$router.replace({name: 'validasi.index.validated'});

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
