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
              <Ibox title="Register Pasien">
                <template v-slot:tools>
                    <button class="btn btn-xs btn-success"><i class="fa fa-upload"></i> Import Data</button>
                    <nuxt-link tag="button" to="/registrasi/mandiri/tambah" class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Registrasi Baru</nuxt-link>
                </template>
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
                    default_sort: 'name',
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
                      sumber_pasien: 'Sumber',
                      no_sampel:'Sampel',
                      tgl_input:'Tanggal Input'
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
          nama: null
        },
    }
  },
  head () {
    return { title: this.$t('home') }
  }
}
</script>
