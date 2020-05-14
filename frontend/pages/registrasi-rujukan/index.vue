<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Register dari Rujukan</portal>
    <portal to="title-action">
      <div class="title-action">
       
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <Ibox title="Register Pasien Rujukan">
          <p
            class="sub-header"
          >Scan / masukan nomor barcode salah satu sampel untuk register pasien rujukan</p>
          <form id="scanbarcode row" action method="post" @submit.prevent="submit" @keydown="form.onKeydown($event)"> 
            <center>
              <div class="form-group">
                <input
                  id="barcodesampel"
                  class="form-control col-md-3"
                  name
                  placeholder="Scan..."
                  type="text"
                  tabindex="1"
                  required
                  autofocus
                  v-model="form.nomor_sampel"
                />
                <br />
                <v-button :loading="form.busy" class="mt-2 btn btn-md btn-primary">
                  <i class="fa fa-plus"></i> Tambahkan Informasi Register
                </v-button>
              </div>
            </center>
          </form>
        </Ibox>
      </div>
    </div>


     <div class="row">
      <div class="col-lg-12">
        <filter-registrasi :oid="`registrasi-rujukan`"/>
      </div>
    </div>

     <div class="row">
      <div class="col-lg-12">
        <Ibox title="Register Rujukan">
                <template v-slot:tools>
                    <nuxt-link tag="button" to="/registrasi/rujukan/import-excel" class="btn btn-xs btn-success"><i class="fa fa-upload"></i> Import Data</nuxt-link>
                    <nuxt-link tag="button" to="/registrasi/rujukan/export-excel" class="btn btn-xs btn-success"><i class="fa fa-dowload"></i> Export Data</nuxt-link>
                    <!-- <nuxt-link tag="button" to="/registrasi/mandiri/tambah" class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Registrasi Baru</nuxt-link> -->
                </template>
                <ajax-table url="/registrasi-mandiri" :oid="'registrasi-rujukan'"
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
                    :rowtemplate="'tr-data-regis-rujukan'"
                    :columns="{
                      nomor_register: 'Nomor Registrasi',
                      nama_pasien: 'Pasien',
                      nama_kota: 'Domisili',
                      nama: 'Instansi Pengirim',
                      no_sampel:'Sampel',
                      tgl_input:'Tanggal Input',
                      keterangan: 'Keterangan'
                    }"></ajax-table>
              </Ibox>
      </div>
    </div>

  </div>
</template>
 
<script>
import Form from "vform";
export default {
  middleware: "auth",
  data() {
    return {
      form: new Form({
        nomor_sampel:null
      }),
      params:{
        jenis_registrasi: "rujukan"
      },
      params1: {
        sam_barcodenomor_sampel: null
      },
      params2: {
        sam_barcodenomor_sampel: null
      }
    };
  },
  head() {
    return { title: "Penerimaan Sampel" };
  },
  methods:{
    async submit(){
      try{
        const response = (await this.form.post("/registrasi-rujukan/cek")).data;
        if(response.status == 200) {
          console.log(response.status)
          // const payload = {
          //       id:response.result.nomor_sampel,
          //       data:response.result
          // }
          // this.$store.dispatch('register/saveData',payload);
          this.$router.push('/registrasi/rujukan/add/'+this.form.nomor_sampel);
        }

        if(response.status == 400) {
          this.$toast.error(response.message, {
            icon: 'times',
            iconPack: 'fontawesome',
            duration: 5000
          })
        }
      }catch(e) {
        this.$toast.error('Terjadi kesalahan server', {
            icon: 'times',
            iconPack: 'fontawesome',
            duration: 5000
         })
        console.log(e)
      }
    }
  }
};
</script>
