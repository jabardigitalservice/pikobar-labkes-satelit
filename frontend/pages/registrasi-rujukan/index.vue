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
                  <i class="fa fa-save"></i> Tambahkan Informasi Register
                </v-button>
              </div>
            </center>
          </form>
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
        const response = await this.form.post("/registrasi-rujukan/cek");
        if(response.status == 200) {
          console.log(response.satus)
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
