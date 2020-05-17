<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Detail Registrasi
    </portal>
    <portal to="title-action">
      <div class="title-action">
        <nuxt-link to="/registrasi/mandiri" class="btn btn-primary">Kembali</nuxt-link>
      </div>
    </portal>
    <div class="row">
      <div class="col-lg-12">
        <Ibox :title="`Detail Register Sampel`">
          <template v-slot:tools>
            <button class="btn btn-xs btn-danger" @click="deleteData()"><i class="fa fa-trash"></i> Delete</button>
            <nuxt-link tag="a" :to="`/registrasi/sampel/update/${registerId}/${pasienId}`" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i> Edit</nuxt-link>
          </template>
          <div class="container">
            <div class="row">
              <div class="col-md-6">
                <h3 class="header-title mt-2 mb-2">Informasi Registrasi</h3>
                <table class="table">
                  <tbody>
                    <!-- <tr>
                      <td width="40%"><b>Nomor Registrasi</b></td>
                      <td width="60%">{{data?data.reg_no:null}}</td>
                    </tr> -->
                     <tr>
                      <td width="40%"><b>Kewarganegaraan</b></td>
                      <td width="60%">{{data?data.reg_kewarganegaraan:null}}</td>
                    </tr>
                     <!-- <tr>
                      <td width="40%"><b>Sumber Pasien</b></td>
                      <td width="60%">{{data?data.reg_sumberpasien:null}}</td>
                    </tr> -->
                  </tbody>
                </table>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <h3 class="header-title mt-2 mb-2">Identitas Pasien</h3>
                <table class="table">
                  <tbody>
                     <tr>
                      <td width="40%"><b>NIK</b></td>
                      <td width="60%">{{data?data.reg_nik:null}}</td>
                    </tr>
                    <tr>
                      <td width="40%"><b>Nama Lengkap Pasien</b></td>
                      <td width="60%">{{data?data.reg_nama_pasien:null}}</td>
                    </tr>
                    <tr>
                      <td width="40%"><b>Jenis Kelamin</b></td>
                      <td width="60%">{{data?(data.reg_jk=='L'?"Laki-Laki":"Perempuan"):null=='P'?'Perempuan':'Laki-laki'}}</td>
                    </tr>
                    <tr>
                      <td width="40%"><b>Tempat Lahir</b></td>
                      <td width="60%">{{data?data.reg_tempatlahir:null}}</td>
                    </tr>
                    <tr>
                      <td width="40%"><b>Tanggal Lahir</b></td>
                      <td width="60%">{{data.reg_tgllahir?data.reg_tgllahir.substr(0,10):''}}</td>
                    </tr>
                    <!-- <tr>
                      <td width="40%"><b>Suhu</b></td>
                      <td width="60%">{{data?data.reg_suhu:null}}</td>
                    </tr> -->
                  </tbody>
                </table>
              </div>

              <div class="col-md-6">
                <h3 class="header-title mt-2 mb-2">Alamat Pasien</h3>
                <table class="table">
                  <tbody>
                     <tr>
                      <td width="40%"><b>Kota/Kabupaten</b></td>
                      <td width="60%">{{data?data.nama_kota:null}}</td>
                    </tr>
                    <tr>
                      <td width="40%"><b>Kecamatan</b></td>
                      <td width="60%">{{data?data.reg_kecamatan:null}}</td>
                    </tr>
                    <tr>
                      <td width="40%"><b>Kelurahan/Desa</b></td>
                      <td width="60%">{{data?(data.reg_kelurahan):null=='P'?'Perempuan':'Laki-laki'}}</td>
                    </tr>
                    <tr>
                      <td width="40%"><b>Rt / Rw</b></td>
                      <td width="60%">{{data?'Rt.'+data.reg_rt+' / Rw.'+data.reg_rw:null}}</td>
                    </tr>
                    <tr>
                      <td width="40%"><b>Alamat Lengkap</b></td>
                      <td width="60%">{{data.reg_alamat?data.reg_alamat:'-'}}</td>
                    </tr>
                  </tbody>
                </table>

              </div>

              <div class="col-md-6">
                <h3 class="header-title mt-2 mb-2">Identitas Sampel</h3>
                <table class="table">
                  <tbody>
                     <tr>
                      <td width="40%"><b>Nomor Sampel</b></td>
                      <td width="60%">
                        <template v-if="data && data.reg_sampel">
                          <p class="badge badge-primary" v-for="item in data.reg_sampel" :key="item.nomor">#{{item.nomor}}</p>
                        </template>
                      </td>
                    </tr>
                    <tr>
                      <td width="40%"><b>Keterangan</b></td>
                      <td width="60%">{{data?data.reg_keterangan:null}}</td>
                    </tr>
                  </tbody>
                </table>

              </div>
            </div>
            </div>

        </Ibox>
      </div>
    </div>
  </div>
</template>

<script>
  import {
    mapActions,
    mapState
  } from 'vuex' 
  import axios from 'axios'

  export default {
    middleware: 'auth',
    async asyncData({route, store}) {
        let error = false;
        let resp = await axios.get("/v1/register/mandiri/"+route.params.register_id+"/"+route.params.pasien_id);
        return {
          data: resp.data
        }
    },
    computed: {
      registerId(){
        return this.$route.params.register_id;
      },
      pasienId(){
        return this.$route.params.pasien_id;
      }
    },
    methods: {
      async deleteData(){
        await axios.delete('v1/register/sampel/'+this.registerId+'/'+this.pasienId)
          .then((response)=>{
              this.$toast.success(response.data.message, {
                  icon: 'check',
                  iconPack: 'fontawesome',
                  duration: 5000
              })

              this.$bus.$emit('refresh-ajaxtable', 'registrasi-sampel')
              this.$router.push('/registrasi/sampel')
          })
          .catch((error)=>{
              this.$swal.fire(
                  'Terjadi Kesalahan',
                  'Gagal menghapus data, silakan hubungi admin',
                  'error'
              );
          })
      }
    },
    head() {
      return {
        title: this.$t('home')
      }
    },
    created(){
      // this.getData();
    }
  }
</script>