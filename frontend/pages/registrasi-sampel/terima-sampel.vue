<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Terima Sampel
    </portal>
    <portal to="title-action">
      <div class="title-action">
        <button v-show="!isHasAction" tag="button" class="btn btn-primary" data-toggle="modal"
          data-target="#terimaSampel">
          <i class="fa fa-check" /> Registrasi Pasien
        </button>
        <nuxt-link to="/registrasi/sampel" class="btn btn-black">
          <i class="uil-arrow-left" /> Kembali
        </nuxt-link>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <filter-perujuk :oid="`registrasi-perujuk`" />
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Register Pasien Dari Perujuk">
          <ajax-table url="v1/register-perujuk" :oid="'registrasi-perujuk'" :params="params"
            :disableSort="['keterangan']" :config="{
              autoload: true,
              has_number: true,
              has_entry_page: true,
              has_pagination: true,
              has_action: isHasAction,
              has_search_input: false,
              custom_header: '',
              default_sort: 'tgl_input',
              default_sort_dir:'desc',
              custom_empty_page: true,
              class: {
                table: [],
                wrapper: ['table-responsive'],
              }
            }" :rowtemplate="'tr-data-terima-sampel'" :columns="{
							checkbox_sampel_id: '#',
              nama_pasien: 'PASIEN',
              nama_kota: 'DOMISILI',
              sumber_pasien:'KATEGORI',
              tgl_input:'TANGGAL SAMPLING',
              no_sampel:'SAMPEL',
              perujuk: 'PERUJUK SAMPEL'
            }" />
        </Ibox>
      </div>
    </div>

    <custom-modal modal_id="terimaSampel" title="Terima Sampel">
      <div slot="body">
        <div class="col-lg-12">
          <p>Jumlah Sampel: {{ selectedNomorSampels.length }}</p>
          <div class="form-group row col-md-12" v-for="(item,idx) in selectedNomorSampels" :key="idx">
            <div class="col-md-2">
              {{ idx + 1 }}
            </div>
            <div class="col-md-5 flex-left">
              <span class="badge badge-white" style="font-size: 1.1em; text-align:left; padding:5px; margin: 0 10px 10px 0;" :class="{'text-black': item.valid, 'text-red': !item.valid}">
              {{ item.name || '' }}
              </span>
            </div>
          </div>
        </div>
        <button @click="submitSampel()" :disabled="loading" :class="{'btn-loading': loading}"
          class="btn btn-md btn-primary block m-b pull-right" type="button">
          <i class="fa fa-check" /> Submit
        </button>
      </div>
    </custom-modal>

  </div>
</template>

<script>
  import $ from "jquery";
  import CustomModal from "~/components/CustomModal";
  const JQuery = $;
  export default {
    middleware: ['auth', 'checkrole'],
    meta: {
      allow_role_id: [8]
    },
    components: {
      CustomModal,
    },
    data() {
      let selectedNomorSampels = this.$store.state.registrasi_perujuk.selectedSampels;
      return {
        loading: false,
        dataError: [],
        params: {
          nama_pasien: null,
          nomor_register: null,
          nomor_sampel: null,
          start_date: null,
          end_date: null
        },
        selectedNomorSampels,
        listSampels: [],
        isHasAction: true,
      }
    },
    head() {
      return {
        title: this.$t('home')
      }
    },
    methods: {
      async submitSampel() {
        JQuery('#terimaSampel').modal('hide')
        const idArray = []
        for (let i = 0; i < this.selectedNomorSampels.length; i++) {
          idArray.push(this.selectedNomorSampels[i].id)
        }
        try {
          const response = await this.$axios.post('/v1/register-perujuk/bulk', {
            id: idArray
          })
          this.$toast.success(response.message, {
            icon: 'check',
            iconPack: 'fontawesome',
            duration: 5000
          })
          this.isHasAction = true;
          this.$store.commit('registrasi_perujuk/clear');
          this.$bus.$emit('refresh-ajaxtable', 'registrasi-perujuk');
        } catch (e) {
          this.$swal.fire("Terjadi kesalahan", "Silakan hubungi Admin", "error");
        }
      }
    },
    watch: {
      'selectedNomorSampels': function (newVal, oldVal) {
        this.selectedNomorSampels.length === 0 ? this.isHasAction = true : this.isHasAction = false
      }
    }
  }
</script>