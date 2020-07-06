<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Detail Hasil Pemeriksaan</portal>
    <portal to="title-action">
      <div class="title-action">
        <router-link :to="'/hasil-pemeriksaan/edit/' + this.data.id" class="btn btn-warning">
          <i class="fa fa-edit"></i> Ubah
        </router-link>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-6">
        <Ibox title="Informasi Sampel">
          <table class="table table-hover">
            <tbody>
              <tr>
                <td width="40%">
                  <b>Nomor Sampel</b>
                </td>
                <td width="60%">
                  <span>{{data.nomor_sampel}}</span>
                </td>
              </tr>

              <tr>
                <td width="40%">
                  <b>Jenis Sampel</b>
                </td>
                <td width="60%">
                  <span>{{ data.jenis_sampel }}</span>
                </td>
              </tr>

              <tr>
                <td width="40%">
                  <b>Swab ke</b>
                </td>
                <td width="60%">
                  <span>{{ data.swab_ke }}</span>
                </td>
              </tr>

              <tr>
                <td width="40%">
                  <b>Tanggal Swab</b>
                </td>
                <td width="60%">
                  <span>{{ data.tanggal_swab | formatDate }}</span>
                </td>
              </tr>
            </tbody>
          </table>
        </Ibox>
        <Ibox title="Informasi Pengirim">
          <table class="table table-hover">
            <tbody>
              <tr>
                <td width="40%">
                  <b>Instansi Pengirim</b>
                </td>
                <td width="60%">
                  <span>{{data.instansi_pengirim}}</span>
                </td>
              </tr>
              <tr>
                <td width="40%">
                  <b>Nama Rumah Sakit/Dinkes</b>
                </td>
                <td width="60%">
                  <span>{{ data.instansi_pengirim_nama }}</span>
                </td>
              </tr>
            </tbody>
          </table>
        </Ibox>
      </div>
      <div class="col-lg-6">
        <Ibox title="Informasi Pasien">
          <table class="table table-hover">
            <tbody>
              <tr>
                <td width="30%">
                  <b>Nama Pasien</b>
                </td>
                <td width="60%">
                  <span>{{data.nama_lengkap}}</span>
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>Nomor Induk Kependudukan</b>
                </td>
                <td width="60%">
                  <span>{{data.nik }}</span>
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>Tempat, Tanggal Lahir Pasien</b>
                </td>
                <td width="60%">
                  <span>{{ data.tempat_lahir }} {{data.tanggal_lahir | formatDate }} - {{ umurPasien }}</span>
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>Jenis Kelamin</b>
                </td>
                <td width="60%">
                  <span v-if="data.jenis_kelamin === 'L'">Laki - laki</span>
                  <span v-if="data.jenis_kelamin === 'P'">Perempuan</span>
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>Kabupaten/Kota</b>
                </td>
                <td width="60%">
                  <span v-if="data.nama_kota">{{ data.nama_kota }}</span>
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>Kecamatan</b>
                </td>
                <td width="60%">
                  <span>{{ data.kecamatan }}</span>
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>Kelurahan</b>
                </td>
                <td width="60%">
                  <span>{{ data.kelurahan }}</span>
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>RT/RW</b>
                </td>
                <td width="60%">
                  <span>{{ data.no_rt }}</span>
                  <span v-if="data.no_rw">/</span>
                  <span>{{ data.no_rw }}</span>
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>Alamat</b>
                </td>
                <td width="60%">
                  <span>{{ data.alamat_lengkap }}</span>
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>No. Telp/HP</b>
                </td>
                <td width="60%">
                  <span v-if="data.no_hp">{{data.no_hp }}</span>
                  <span v-else-if="data.no_telp">{{data.no_telp }}</span>
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>Tanggal Registrasi</b>
                </td>
                <td width="60%">
                  <span>{{ data.created_at | formatDate }}</span>
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>Dinkes Pengirim</b>
                </td>
                <td width="60%">
                  <span>{{ data.instansi_pengirim }}</span>
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>Status</b>
                </td>
                <td width="60%">
                  <span>{{ data.status }}</span>
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>Kategori</b>
                </td>
                <td width="60%">
                  <span>{{ data.sumber_pasien }}</span>
                </td>
              </tr>
            </tbody>
          </table>
        </Ibox>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-6">
            <Ibox title="Pemeriksaan Sampel">
              <table class="table">
                <tbody>
                  <tr>
                    <td>
                      <b>Tanggal Input Hasil</b>
                    </td>
                    <td>{{ data.waktu_pcr_sample_analyzed | formatDate }}</td>
                  </tr>
                  <tr>
                    <td>
                      <b>Kesimpulan Pemeriksaan</b>
                    </td>
                    <td>{{ data.kesimpulan_pemeriksaan }}</td>
                  </tr>
                </tbody>
              </table>

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
              <table class="table table-hover">
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
    </div>
  </div>
</template>

<script>
  import axios from "axios";

  export default {
    middleware: "auth",
    async asyncData({
      route
    }) {
      let resp = await axios.get("/v1/verifikasi/detail/" + route.params.id);
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
    computed: {
      umurPasien() {
        if (this.data.tanggal_lahir) {
          let tglLahir = new Date(this.data.tanggal_lahir);
          let today_date = new Date();
          let today_year = today_date.getFullYear();
          let today_month = today_date.getMonth();
          let today_day = today_date.getDate();

          var age = today_date.getFullYear() - tglLahir.getFullYear();
          var m = today_date.getMonth() - tglLahir.getMonth();
          if (m < 0 || (m === 0 && today_date.getDate() < tglLahir.getDate())) {
            age--;
          }

          return `Usia: ${age} tahun`;
        }
        if (this.data.usia_tahun) {
          return `Usia: ${this.data.usia_tahun} tahun`;
        }
        return "";
      }
    },
    head() {
      return {
        title: "Detil Sampel Hasil Pemeriksaan"
      };
    }
  };
</script>

<style scoped>
  .table-sub-head {
    padding-top: 25px;
  }
</style>