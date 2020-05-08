<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Import Registrasi Rujukan</portal>
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
                id="register_file"
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
import $ from "jquery";

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

        await axios.post(process.env.apiUrl + "/v1/register/import-rujukan", formData, {
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
          
          if (err.response && err.response.data.code == 422) {

            for (const property in err.response.data.error) {
              // const element = array[property];
              this.$toast.error(err.response.data.error[property][0], {
                icon: "times",
                iconPack: "fontawesome",
                duration: 5000
              });
              
            }

          }
          
          if (err.response && err.response.data.code == 403) {
              this.$toast.error(err.response.data.error, {
                icon: "times",
                iconPack: "fontawesome",
                duration: 5000
              });
          }

          if (err.response && err.response.data.code == 500) {
              this.$swal.fire(
                "Terjadi kesalahan",
                "Silakan hubungi Admin",
                "error"
              );
          }
      }

      $('#register_file').val('');
      this.form.reset();

      this.loading = false;
    },
    previewFile(){
        this.form.register_file = this.$refs.myFile.files[0]      
    }
  }
};
</script>