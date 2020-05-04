<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Import Registrasi Mandiri</portal>
    <portal to="title-action">
      <div class="title-action">
        <a href="#" @click.prevent="$router.back()" class="btn btn-secondary">
          <i class="uil-arrow-left"></i>Kembali
        </a>
      </div>
    </portal>
    <form @submit.prevent="dummy">
      <div class="row">
        
        <div class="col-md-6">
          <Ibox title="Import">

            <div class="form-group">
              <label for="register_file">
                File Registrasi
              </label>
              <input class="form-control" 
                type="file" 
                ref="myFile"  
                @change="previewFile"
              >
            </div>

            <div class="form-group">
              <button
                @click="submit()"
                :disabled="loading"
                :class="{'btn-loading': loading}"
                class="btn btn-md btn-primary block full-width m-b"
                type="button"
              >
                <i class="fa fa-check"></i>
                Import Excel
              </button>
            </div>
          </Ibox>
        </div>

      </div>

    </form>
  </div>
</template>

<script>
import Form from "vform";
import axios from "axios";

export default {
  middleware: "auth",

  data() {

    return {
      loading: false,
    };
  },

  async asyncData({ route, store }) {

    let form = new Form({
      register_file: null
    });

    return {
      form,
    };
  },

  head() {
    return {
      title: "Export Excel Hasil Verifikasi"
    };
  },
  methods: {

    dummy() {
      return false;
    },

    async submit() {


      let formData = new FormData();

      formData.append('register_file', this.form.register_file);

      this.loading = true;

      try {   
        
        // this.$toast.show('Importing in...')

        await axios.post(process.env.apiUrl + "/v1/register/import-mandiri", formData, {
          headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        this.$toast.success('Sukses import data', {
            icon: "check",
            iconPack: "fontawesome",
            duration: 5000
          });
        
      } catch (err) {

          console.log('ERRRR', err);
          
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

      
      

      // try {
      //   this.loading = true;

      //   axios({
      //           url: process.env.apiUrl + "/v1/register/import-mandiri",
      //           params: this.form,
      //           method: 'POST',
      //           responseType: 'json',
      //       }).then((response) => {
                
      //       });

      // } catch (err) {
      //   if (err.response && err.response.data.code == 422) {

      //     this.$nextTick(() => {
      //       this.form.errors.set(err.response.data.error);
      //     });

      //     this.$toast.error("Mohon cek kembali formulir Anda", {
      //       icon: "times",
      //       iconPack: "fontawesome",
      //       duration: 5000
      //     });

      //   } else {

      //     this.$swal.fire(
      //       "Terjadi kesalahan",
      //       "Silakan hubungi Admin",
      //       "error"
      //     );

      //   }
      // }
      // this.loading = false;





      // try {
      //   this.loading = true;

      //   const response = await this.form.post("/v1/register/import-mandiri");

      //   this.$toast.success(response.data.message, {
      //     icon: "check",
      //     iconPack: "fontawesome",
      //     duration: 5000
      //   });

      // } catch (err) {

      //   console.log(err)

      //   if (err.response && err.response.data.code == 422) {

      //     this.$nextTick(() => {
      //       this.form.errors.set(err.response.data.error);
      //     });

      //     this.$toast.error("Mohon cek kembali formulir Anda", {
      //       icon: "times",
      //       iconPack: "fontawesome",
      //       duration: 5000
      //     });

      //   } else {

      //     this.$swal.fire(
      //       "Terjadi kesalahan",
      //       "Silakan hubungi Admin",
      //       "error"
      //     );

      //   }
      // }
      // this.loading = false;

    },
    previewFile(){
      this.form.register_file = this.$refs.myFile.files[0]
      
    }
  }
};
</script>