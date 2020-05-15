<template>
      <div class="wrapper wrapper-content">
        <portal to="title-name">
          Registrasi Mandiri
        </portal>
        <portal to="title-action">
          <div class="title-action">
          </div>
        </portal>

        <div class="row">
          <div class="col-lg-12">
            <filter-registrasi :oid="`registrasi-mandiri`"/>
          </div>
        </div>

          <div class="row">
            <div class="col-lg-12">
              <Ibox title="Register Pasien">
                <template v-slot:tools>
                  <div class="d-sm-block d-none">
                    <nuxt-link tag="button" to="/registrasi/mandiri/import-excel" class="btn btn-xs btn-success"><i class="fa fa-upload"></i> Import Data</nuxt-link>
                    <nuxt-link tag="button" to="/registrasi/mandiri/export-excel" class="btn btn-xs btn-success"><i class="fa fa-dowload"></i> Export Data</nuxt-link>
                    <nuxt-link tag="button" to="/registrasi/mandiri/tambah" class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Registrasi Baru</nuxt-link>
                  </div>
                </template>
                <div class="d-sm-none mb-2">
                  <nuxt-link tag="button" to="/registrasi/mandiri/import-excel" class="mb-1 btn btn-xs btn-success"><i class="fa fa-upload"></i> Import Data</nuxt-link>
                    <nuxt-link tag="button" to="/registrasi/mandiri/export-excel" class="mb-1 btn btn-xs btn-success"><i class="fa fa-dowload"></i> Export Data</nuxt-link>
                    <nuxt-link tag="button" to="/registrasi/mandiri/tambah" class="mb-1 btn btn-xs btn-success"><i class="fa fa-plus"></i> Registrasi Baru</nuxt-link>
                </div>
                <hr>
                <ajax-table url="/registrasi-mandiri" :oid="'registrasi-mandiri'"
                  :params="params"
                  :config="{
                    autoload: true,
                    has_number: true,
                    has_entry_page: true,
                    has_pagination: true,
                    has_action: true,
                    has_search_input: true,
                    custom_header: '',
                    default_sort: 'created_at',
                    default_sort_dir:'desc',
                    custom_empty_page: true,
                    class: {
                        table: [],
                        wrapper: ['table-responsive'],
                    }
                    }"
                    :rowtemplate="'tr-data-regis-mandiri'"
                    :columns="{
                      nomor_register: 'Nomor Registrasi',
                      nama_pasien: 'Pasien',
                      nama_kota: 'Domisili',
                      sumber_pasien: 'Kategori',
                      no_sampel:'Sampel',
                      tgl_input:'Tanggal Input',
                      keterangan:'Keterangan'
                    }"></ajax-table>
              </Ibox>
            </div>
        </div>
      </div>
</template>
 
<script>

export default {
  middleware: 'auth',
  data(){
    return{
       params: {
          jenis_registrasi: "mandiri",
          nama_pasien: null,
          nomor_register:null,
          nomor_sampel:null,
          start_date:null,
          end_date:null
        },
    }
  },
  head () {
    return { title: this.$t('home') }
  },
  filter(){
  },
  methods:{
    doFilter(){
      this.$bus.$emit('refresh-ajaxtable','registrasi-mandiri');
    }
  }
}
</script>
