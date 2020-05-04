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
              <Ibox title="Filter Data">  

                <div class="form-group row">
                  <div class="col-md-2">
                  <label for="nama_pasien">Nama Pasien</label>
                  </div>
                  <div class="col-md-4">
                    <input type="text" name="nama_pasien" v-model="params.nama_pasien" id="" class="form-control" placeholder="Ketikkan Nama Pasien">
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-md-2">
                  <label for="nama_pasien">Nomor Register</label>
                  </div>
                  <div class="col-md-4">
                    <input type="text" name="nomor_register" v-model="params.nomor_register" id="" class="form-control" placeholder="Nomor Register">
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-md-2">
                  <label for="nama_pasien">Nomor Sampel</label>
                  </div>
                  <div class="col-md-4">
                    <input type="text" name="nomor_sampel" v-model="params.nomor_sampel" id="" class="form-control" placeholder="Scan / Ketik No. Sampel">
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-md-2">
                  <label for="nama_pasien">Tanggal Input</label>
                  </div>
                  <div class="col-md-4">
                    <date-picker placeholder="Tanggal Mulai Input" format="d MMMM yyyy"
                          input-class="form-control" :monday-first="true"
                          v-model="params.start_date" />
                  </div>
                  <div class="col-md-1">
                    S.d
                  </div>
                  <div class="col-md-4">
                    <date-picker placeholder="Tanggal Akhir Input" format="d MMMM yyyy"
                          input-class="form-control" :monday-first="true"
                          v-model="params.end_date" />
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-md-12 text-left">
                    <button class="btn btn-primary" style="width:200px;margin-top:20px" @click="doFilter"><i class="fa fa-eye"></i> Filter</button>

                  </div>
                </div>

              </Ibox>
            </div>
        </div>

          <div class="row">
            <div class="col-lg-12">
              <Ibox title="Register Pasien">
                <template v-slot:tools>
                    <button class="btn btn-xs btn-success"><i class="fa fa-upload"></i> Import Data</button>
                    <nuxt-link tag="button" to="/registrasi/mandiri/export-excel" class="btn btn-xs btn-success"><i class="fa fa-download"></i> Export Data</nuxt-link>
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
