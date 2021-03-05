<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Detail Registrasi
    </portal>
    <portal to="title-action">
      <div class="title-action">
        <nuxt-link tag="a" :to="`/registrasi/sampel/update/${registerId}/${pasienId}`" class="btn btn-import-export" v-if="data.reg_sampel_status == 'sample_taken' && !data.reg_register_perujuk_id">
          <i class="fa fa-pencil" /> Perbarui Data
        </nuxt-link>
        <nuxt-link to="/registrasi/sampel" class="btn btn-black">
          <i class="uil-arrow-left" /> Kembali
        </nuxt-link>
      </div>
    </portal>

    <div class="col-lg-12 row">
      <div class="col-md-6">
        <Ibox title="Identitas Pengirim">
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Instansi Pengirim
            </div>
            <div class="col-md-7 flex-text-center">
              {{ instansiPengirim }}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Nama Rumah Sakit/Dinkes
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_nama_rs || null}}
            </div>
          </div>
        </Ibox>

        <Ibox title="Identitas Pasien">
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              NIK
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_nik || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Nama Lengkap Pasien
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_nama_pasien || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Jenis Kelamin
            </div>
            <div class="col-md-7 flex-text-center">
              {{data && data.reg_jk ? data.reg_jk === "L" ? "Laki-Laki" : "Perempuan" : null }}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Tempat Lahir
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_tempatlahir || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Tanggal Lahir
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_tgllahir | formatDate}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kategori
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_sumber_pasien || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kriteria Pasien
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_status ? pasienStatus.find(x => x.value == data.reg_status).text : null }}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Usia
            </div>
            <div class="col-md-7 flex-text-center">
              {{usiaPasien}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              No HP
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_nohp}}
            </div>
          </div>
        </Ibox>
      </div>

      <div class="col-md-6">
        <Ibox title="Alamat Pasien">
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kota / Kabupaten
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_nama_kota || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kecamatan
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_nama_kecamatan || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kelurahan/Desa
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_nama_kelurahan || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              RT / RW
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_rt ? data.reg_rt : '-'}} / {{data.reg_rw ? data.reg_rw: '-'}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Alamat Lengkap
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_alamat || null}}
            </div>
          </div>
        </Ibox>

        <Ibox title="Identitas Sampel">
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Nomor Sampel
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_sampel_nomor || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Swab ke
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_swab_ke || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Tanggal Swab
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_tanggal_swab | formatDate}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Keterangan
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.reg_keterangan || null}}
            </div>
          </div>
        </Ibox>
        <Ibox title="Riwayat Perubahan">
          <div class="col-12">
           <Timeline :data="logs" />
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
  import {
    getHumanAge, humanize
  } from '~/utils';
  import {
    pasienStatus
  } from '~/assets/js/constant/enum';

  export default {
    middleware: 'auth',
    async asyncData({
      route,
      store
    }) {
      let error = false;
      let resp = await axios.get("/v1/register/sampel/" + route.params.register_id + "/" + route.params.pasien_id);
      return {
        data: resp.data
      }
    },
    data() {
      return {
        pasienStatus,
        logs: null
      }
    },
    computed: {
      registerId() {
        return this.$route.params.register_id;
      },
      pasienId() {
        return this.$route.params.pasien_id;
      },
      usiaPasien() {
        if (this.data && this.data.reg_usia_tahun) {
          if (this.data && this.data.reg_usia_bulan) {
            return `${this.data.reg_usia_tahun} tahun ${this.data.reg_usia_bulan} bulan`;
          }
          return `${this.data.reg_usia_tahun} tahun`;
        }
        if (this.data.reg_tgllahir) {
          return getHumanAge(this.data.reg_tgllahir);
        }
        return "";
      },
      instansiPengirim() {
        if(this.data.reg_fasyankes_pengirim) {
          return humanize(this.data.reg_fasyankes_pengirim)
        }
        return ""
      }
    },
    methods: {
      async deleteData() {
        await axios.delete('v1/register/sampel/' + this.registerId + '/' + this.pasienId)
          .then((response) => {
            this.$toast.success(response.data.message, {
              icon: 'check',
              iconPack: 'fontawesome',
              duration: 5000
            })

            this.$bus.$emit('refresh-ajaxtable', 'registrasi-sampel')
            this.$router.push('/registrasi/sampel')
          })
          .catch((error) => {
            this.$swal.fire(
              'Terjadi Kesalahan',
              'Gagal menghapus data, silakan hubungi admin',
              'error'
            );
          })
      },
      getLogs(id) {
        let url = `v1/register/logs/${id}`
        let self = this
        axios
          .get(url)
          .then(function (response) {
              self.logs = response.data.result; // Data existed
          })
          .catch(function (err) {
              console.log(err);
        });
      }
    },
    head() {
      return {
        title: this.$t('home')
      }
    },
    created() {
      this.getLogs(this.$route.params.register_id)
    },
  }
</script>