<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Detail Registrasi
    </portal>
    <portal to="title-action">
      <div class="title-action">
        <!-- <nuxt-link tag="a" :to="`/registrasi/sampel/update/${registerId}`" class="btn btn-import-export">
          <i class="fa fa-pencil" /> Perbarui Data
        </nuxt-link> -->
        <nuxt-link to="/registrasi/perujuk" class="btn btn-black">
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
              {{ data.result.fasyankes ? humanize(data.result.fasyankes.tipe) : '' }}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Nama Rumah Sakit/Dinkes
            </div>
            <div class="col-md-7 flex-text-center">
              {{ data.result.fasyankes ? data.result.fasyankes.nama : '' }}
            </div>
          </div>
        </Ibox>

        <Ibox title="Identitas Pasien">
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              NIK
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.result.nik || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Nama Lengkap Pasien
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.result.nama_pasien || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Jenis Kelamin
            </div>
            <div class="col-md-7 flex-text-center">
              {{data && data.result.jenis_kelamin ? data.result.jenis_kelamin === "L" ? "Laki-Laki" : "Perempuan" : null }}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Tempat Lahir
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.result.tempat_lahir || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Tanggal Lahir
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.result.tanggal_lahir | formatDate}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kategori
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.result.sumber_pasien || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Status Pasien
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.result.status || null }}
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
              {{data.result.no_hp}}
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
              {{data.result.kota || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kecamatan
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.result.kecamatan || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kelurahan/Desa
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.result.kelurahan || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              RT / RW
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.result.no_rt ? data.result.no_rt : '-'}} / {{data.result.no_rw ? data.result.no_rw: '-'}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Alamat Lengkap
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.result.alamat || null}}
            </div>
          </div>
        </Ibox>

        <Ibox title="Identitas Sampel">
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Nomor Sampel
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.result.nomor_sampel || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Swab ke
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.result.swab_ke || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Tanggal Swab
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.result.tanggal_swab | formatDate}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Keterangan
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.result.keterangan || null}}
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
      let resp = await axios.get("/v1/register-perujuk/detail/" + route.params.id);
      return {
        data: resp.data
      }
    },
    data() {
      return {
        pasienStatus,
        humanize,
      }
    },
    computed: {
      registerId() {
        return this.$route.params.id;
      },
      usiaPasien() {
        if (this.data && this.data.result.usia_tahun) {
          if (this.data && this.data.result.usia_bulan) {
            return `${this.data.result.usia_tahun} tahun ${this.data.result.reg_usia_bulan} bulan`;
          }
          return `${this.data.result.usia_tahun} tahun`;
        }
        if (this.data.result.tanggal_lahir) {
          return getHumanAge(this.data.result.tanggal_lahir);
        }
        return "";
      },
    },
    methods: {
      async deleteData() {
        await axios.delete('v1/register-perujuk/' + this.registerId)
          .then((response) => {
            this.$toast.success(response.data.result.message, {
              icon: 'check',
              iconPack: 'fontawesome',
              duration: 5000
            })

            this.$bus.$emit('refresh-ajaxtable', 'registrasi-perujuk')
            this.$router.push('/registrasi/perujuk')
          })
          .catch((error) => {
            this.$swal.fire(
              'Terjadi Kesalahan',
              'Gagal menghapus data, silakan hubungi admin',
              'error'
            );
          })
      },
    },
    head() {
      return {
        title: this.$t('home')
      }
    },
  }
</script>