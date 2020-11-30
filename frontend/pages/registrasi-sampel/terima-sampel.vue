<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Terima Sampel
    </portal>
    <portal to="title-action">
      <div class="title-action">
        <button tag="button" class="btn btn-default" data-toggle="modal" data-target="#terimaSampel">
          <i class="fa fa-check" /> Terima Sampel
        </button>
        <nuxt-link to="/registrasi/sampel" class="btn btn-black">
          <i class="uil-arrow-left" /> Kembali
        </nuxt-link>
      </div>
    </portal>

    <!-- <div class="row">
      <div class="col-lg-12">
        <filter-registrasi :oid="`registrasi-sampel`" />
      </div>
    </div> -->

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Register Pasien Dari Perujuk">
          <ajax-table url="v1/register-perujuk" :oid="'registrasi-perujuk'" :params="params"
            :disableSort="['keterangan']" :config="{
              autoload: true,
              has_number: true,
              has_entry_page: true,
              has_pagination: true,
              has_action: false,
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
              no_sampel:'SAMPEL',
              kode_kasus : 'KODE KASUS',
              nama_pasien: 'PASIEN',
              nama_kota: 'DOMISILI',
              sumber_pasien:'KATEGORI',
              status:'STATUS',
              keterangan:'KETERANGAN'
            }" />
        </Ibox>
      </div>
    </div>

    <custom-modal modal_id="terimaSampel" title="Terima Sampel">
      <div slot="body">
        <div class="col-lg-12">
          <p>Jumlah Sampel: {{ selectedNomorSampels.length }}</p>
          <div class="badge badge-white" style="text-align:left; padding:5px; margin: 0 10px 10px 0;"
            v-for="(item,idx) in selectedNomorSampels" :key="idx">
              <span :class="{'text-black': item.valid, 'text-red': !item.valid}" style="font-size: 1.1em">
                {{item}}
              </span>
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
  import Form from "vform";
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
      }
    },
    head() {
      return {
        title: this.$t('home')
      }
    },
    methods: {
      // async getCountry() {
      //   let resp = await this.$axios.get('/v1/register-perujuk/');
      //   this.optionCountry = resp.data;
      //   this.country = this.country ? this.optionCountry.find(el => el.nama === this.country) : null;
      // },
      removeSample(index) {
        this.selectedNomorSampels.splice(index, 1);
      },
      submitSampel() {
        JQuery('#terimaSampel').modal('hide');
      }
    }
  }
</script>