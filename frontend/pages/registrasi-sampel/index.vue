<template>
      <div class="wrapper wrapper-content">
        <portal to="title-name">
          Registrasi Sampel
        </portal>
        <portal to="title-action">
          <div class="title-action">
          </div>
        </portal>

        <div class="row">
          <div class="col-lg-12">
            <filter-registrasi :oid="`registrasi-sampel`"/>
          </div>
        </div>

          <div class="row">
            <div class="col-lg-12">
              <Ibox title="Register Pasien">
                <template v-slot:tools>
                  <div class="d-sm-block d-none">
                    <nuxt-link tag="button" to="/registrasi/sampel/import-excel" class="btn btn-xs btn-success"><i class="fa fa-upload"></i> Import Data</nuxt-link>
                    <!-- <nuxt-link tag="button" to="/registrasi/sampel/export-excel" class="btn btn-xs btn-success"><i class="fa fa-dowload"></i> Export Data</nuxt-link> -->
                    <nuxt-link tag="button" to="/registrasi/sampel/tambah" class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Registrasi Baru</nuxt-link>
                  </div>
                </template>
                <div class="d-sm-none mb-2">
                  <nuxt-link tag="button" to="/registrasi/sampel/import-excel" class="mb-1 btn btn-xs btn-success"><i class="fa fa-upload"></i> Import Data</nuxt-link>
                    <nuxt-link tag="button" to="/registrasi/sampel/export-excel" class="mb-1 btn btn-xs btn-success"><i class="fa fa-dowload"></i> Export Data</nuxt-link>
                    <nuxt-link tag="button" to="/registrasi/sampel/tambah" class="mb-1 btn btn-xs btn-success"><i class="fa fa-plus"></i> Registrasi Baru</nuxt-link>
                </div>
                <hr>
                <ajax-table url="/registrasi-sampel" :oid="'registrasi-sampel'"
                  :params="params"
                  :disableSort="['no_sampel','keterangan']"
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
                    :rowtemplate="'tr-data-regis-sample'"
                    :columns="{
                      nama_pasien: 'Pasien',
                      nama_kota: 'Domisili',
                      instansi_pengirim_nama: 'Nama Rumah Sakit/Dinkes',
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
  middleware: ['auth'],
  data(){
    return{
       params: {
          // lab_satelit_id: 1,
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
      this.$bus.$emit('refresh-ajaxtable','registrasi-sampel');
    }
  }
}
</script>
