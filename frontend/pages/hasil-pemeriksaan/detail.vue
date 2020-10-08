<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Detail Hasil Pemeriksaan</portal>
    <portal to="title-action">
      <div class="title-action">
        <router-link :to="`/hasil-pemeriksaan/edit/${this.data.id}`" class="btn btn-import-export">
          <i class="fa fa-pencil" /> Perbarui Data
        </router-link>
        <nuxt-link to="/hasil-pemeriksaan/list-hasil-pemeriksaan" class="btn btn-black">
          <i class="uil-arrow-left" /> Kembali
        </nuxt-link>
      </div>
    </portal>

    <div class="col-lg-12 row">
      <div class="col-lg-6">
        <Ibox title="Identitas Pengirim">
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Instansi Pengirim
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.instansi_pengirim == 'rumah_sakit' ? 'rumah sakit' : data.instansi_pengirim || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Nama Rumah Sakit/Dinkes
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.instansi_pengirim_nama || null}}
            </div>
          </div>
        </Ibox>
        
        <Ibox title="Identitas Sampel">
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Nomor Sampel
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.nomor_sampel || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Jenis Sampel
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.jenis_sampel || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Swab ke
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.swab_ke || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Tanggal Swab
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.tanggal_swab | formatDate}}
            </div>
          </div>
        </Ibox>
      </div>

      <div class="col-lg-6">
        <Ibox title="Identitas Pasien">
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Nama Lengkap Pasien
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.nama_lengkap || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              NIK
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.nik || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Jenis Kelamin
            </div>
            <div class="col-md-7 flex-text-center">
              {{data && data.jenis_kelamin ? data.jenis_kelamin === "L" ? "Laki-Laki" : "Perempuan" : null }}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Tempat &amp; Tanggal Lahir
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.tempat_lahir || null}} {{data.tanggal_lahir | formatDate }}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Usia Pasien
            </div>
            <div class="col-md-7 flex-text-center">
              {{ usiaPasien }}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kabupaten/Kota
            </div>
            <div class="col-md-7 flex-text-center">
              <span v-if="data.nama_kota">{{ data.nama_kota }}</span>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kecamatan
            </div>
            <div class="col-md-7 flex-text-center">
              <span v-if="data.kecamatan">{{ data.kecamatan }}</span>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kelurahan
            </div>
            <div class="col-md-7 flex-text-center">
              <span v-if="data.kelurahan">{{ data.kelurahan }}</span>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              RT/RW
            </div>
            <div class="col-md-7 flex-text-center">
              <span>{{ data.no_rt }}</span>
              <span v-if="data.no_rw">/</span>
              <span>{{ data.no_rw }}</span>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Alamat
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.alamat_lengkap || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              No. Telp/HP
            </div>
            <div class="col-md-7 flex-text-center">
              <span v-if="data.no_hp">{{data.no_hp }}</span>
              <span v-else-if="data.no_telp">{{data.no_telp }}</span>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Tanggal Registrasi
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.created_at | formatDate}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Dinkes Pengirim
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.instansi_pengirim || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kriteria
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.status ? pasienStatus.find(x => x.value == data.status).text : null  || null}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kategori
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.sumber_pasien || null}}
            </div>
          </div>
        </Ibox>
      </div>
    </div>

    <div class="col-md-12 row">
      <div class="col-md-6">
        <Ibox title="Pemeriksaan Sampel">
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Tanggal Input Hasil
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.waktu_pcr_sample_analyzed | formatDate}}
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-5 text-blue flex-text-center">
              Kesimpulan Pemeriksaan
            </div>
            <div class="col-md-7 flex-text-center">
              {{data.kesimpulan_pemeriksaan || null}}
            </div>
          </div>

          <table class="table dt-responsive table-bordered" style="width:100%">
            <thead>
              <tr>
                <th>Target Gen</th>
                <th>CT Value</th>
              </tr>
            </thead>
            <tbody class="field_wrapper">
              <tr v-for="(hasil, $index) in JSON.parse(data.hasil_deteksi)" :key="$index">
                <td>
                  <p class="form-control">
                    <b>{{ hasil.target_gen }}</b>
                  </p>
                </td>
                <td>
                  <p class="form-control">
                    <b v-if="!!hasil.ct_value">{{ hasil.ct_value }}</b>
                    <b v-if="hasil.ct_value == null">{{ '-' }}</b>
                  </p>
                </td>
              </tr>
            </tbody>
          </table>
        </Ibox>
      </div>

      <!-- Log Sampel -->
      <div class="col-md-6">
        <Ibox title="Log Sampel">
          <table class="table dt-responsive table-hover">
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Perubahan Status</th>
                <th>Deskripsi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="log in data.logs" :key="log.id">
                <td>{{ log.created_at | formatDateTime }}</td>
                <td>{{ log.sampel_status_before }} ==> {{ log.sampel_status }}</td>
                <td>{{ log.description }}</td>
              </tr>
            </tbody>
          </table>
        </Ibox>
      </div>
    </div>
  </div>
</template>

<script>
  import axios from "axios";
  import {
    pasienStatus
  } from '~/assets/js/constant/enum';
  import {
    getHumanAge
  } from '~/utils';

  export default {
    middleware: "auth",
    async asyncData({
      route
    }) {
      let resp = await axios.get(`/v1/verifikasi/detail/${route.params.id}`);
      let data = resp.data.data;

      if (!data.pasien) {
        data.pasien = {};
      }

      if (!data.pengambilanSampel) {
        data.pengambilanSampel = {};
      }

      if (!data.ekstraksi) {
        data.ekstraksi = {};
      }

      if (!data.pemeriksaan_sampel) {
        data.pemeriksaan_sampel = {};
      }

      if (!data.register) {
        data.register = {};
      }

      return {
        data
      };
    },
    data() {
      return {
        pasienStatus,
      }
    },
    computed: {
      usiaPasien() {
        if (this.data.usia_tahun) {
          if (this.data.usia_tahun && this.data.usia_bulan) {
            return `${this.data.usia_tahun} tahun ${this.data.usia_bulan} bulan`;
          }
          return `${this.data.usia_tahun} tahun`;
        }
        if (this.data.tanggal_lahir) {
          return getHumanAge(this.data.tanggal_lahir);
        }
        return "";
      }
    },
    head() {
      return {
        title: "Detail Sampel Hasil Pemeriksaan"
      };
    }
  };
</script>

<style scoped>
  .table-sub-head {
    padding-top: 25px;
  }
</style>