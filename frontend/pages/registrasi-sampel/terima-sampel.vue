<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Terima Sampel
    </portal>
    <portal to="title-action">
      <div class="title-action">
        <nuxt-link to="/registrasi/sampel" class="btn btn-black">
          <i class="uil-arrow-left" /> Kembali
        </nuxt-link>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <filter-registrasi :oid="`registrasi-sampel`" />
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Register Pasien">
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
            }" :rowtemplate="'tr-data-regis-perujuk'" :columns="{
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
      }
    },
    head() {
      return {
        title: this.$t('home')
      }
    },
    methods: {
    }
  }
</script>