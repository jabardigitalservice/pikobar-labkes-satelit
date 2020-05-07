<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">Detail Sampel</portal>
    <portal to="title-action">
      <div class="title-action">
        <router-link :to="'/verifikasi/edit/' + this.data.id" class="btn btn-warning">
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
                  <b>Sumber Sampel</b>
                </td>
                <td width="60%">
                  <span>{{data.sumber_sampel}}</span>
                </td>
              </tr>

              <tr>
                <td width="40%">
                  <b>Petugas Penerima Sampel</b>
                </td>
                <td width="60%">
                  <span>{{data.petugas_penerima_sampel}}</span>
                </td>
              </tr>

              <tr>
                <td width="40%">
                  <b>Tipe Sampel</b>
                </td>
                <td width="60%">
                  <span>{{ data.jenis_sampel }}</span>
                </td>
              </tr>

              <tr>
                <td width="40%">
                  <b>Kondisi Sampel</b>
                </td>
                <td width="60%">
                  <span>{{ data.kondisi_sampel }}</span>
                </td>
              </tr>

              <tr>
                <td colspan="2" class="table-sub-head">
                  <span class="text-muted">PENGAMBILAN SAMPEL</span>
                </td>
              </tr>

              <tr>
                <td width="40%">
                  <b>Waktu Pengambilan</b>
                </td>
                <td width="60%">
                  <span>{{ data.pengambilanSampel.created_at | formatDateTime }}</span>
                  <!-- <span>{{ data.tanggal_pengambilan_sampel | formatDate }} - {{ data.jam_pengambilan_sampel }}</span> -->
                </td>
              </tr>

              <tr>
                <td width="40%">
                  <b>Petugas</b>
                </td>
                <td width="60%">
                  <span>{{ data.pengambilanSampel.penerima_sampel }}</span>
                  <!-- <span>{{ data.tanggal_pengambilan_sampel | formatDate }} - {{ data.jam_pengambilan_sampel }}</span> -->
                </td>
              </tr>
              <tr>
                <td width="40%">
                  <b>Sumber Sampel</b>
                </td>
                <td width="60%">
                  <span>{{ data.pengambilanSampel.sumber_sampel }}</span>
                  <!-- <span>{{ data.tanggal_pengambilan_sampel | formatDate }} - {{ data.jam_pengambilan_sampel }}</span> -->
                </td>
              </tr>

              <tr>
                <td width="40%">
                  <b>Catatan</b>
                </td>
                <td width="60%">
                  <span>{{ data.pengambilanSampel.catatan }}</span>
                </td>
              </tr>

              <tr>
                <td colspan="2" class="table-sub-head text-muted">EKSTRAKSI</td>
              </tr>

              <tr>
                <td width="40%">
                  <b>Tanggal Penerimaan</b>
                </td>
                <td width="60%">
                  <span>{{ data.ekstraksi.tanggal_penerimaan_sampel | formatDate }}</span>
                </td>
              </tr>

              <tr>
                <td width="40%">
                  <b>Jam Penerimaan</b>
                </td>
                <td width="60%">
                  <span>{{ data.ekstraksi.jam_penerimaan_sampel }}</span>
                </td>
              </tr>

              <tr>
                <td width="40%">
                  <b>Petugas Penerimaan</b>
                </td>
                <td width="60%">
                  <span>{{ data.ekstraksi.petugas_penerima_sampel }}</span>
                </td>
              </tr>

              <tr>
                <td width="40%">
                  <b>Catatan Penerimaan</b>
                </td>
                <td width="60%">
                  <span>{{ data.ekstraksi.catatan_penerimaan }}</span>
                </td>
              </tr>

              <tr>
                <td width="40%">
                  <b>Operator Ekstraksi</b>
                </td>
                <td width="60%">
                  <span>{{ data.ekstraksi.operator_ekstraksi }}</span>
                </td>
              </tr>

              <tr>
                <td width="40%">
                  <b>Tanggal Mulai Ekstraksi</b>
                </td>
                <td width="60%">
                  <span>{{ data.ekstraksi.tanggal_mulai_ekstraksi | formatDate }}</span>
                </td>
              </tr>

              <tr>
                <td width="40%">
                  <b>Jam Mulai Ekstraksi</b>
                </td>
                <td width="60%">
                  <span>{{ data.ekstraksi.jam_mulai_ekstraksi }}</span>
                </td>
              </tr>

              <tr>
                <td width="40%">
                  <b>Metode Ekstraksi</b>
                </td>
                <td width="60%">
                  <span>{{ data.ekstraksi.metode_ekstraksi }}</span>
                </td>
              </tr>

              <tr>
                <td width="40%">
                  <b>Nama Kit Ekstraksi</b>
                </td>
                <td width="60%">
                  <span>{{ data.ekstraksi.nama_kit_ekstraksi }}</span>
                </td>
              </tr>

              <tr>
                <td class="table-sub-head text-muted" colspan="2"></td>
              </tr>

              <tr>
                <td width="40%">
                  <b>Lab Penerima</b>
                </td>
                <td width="60%">
                  <span>{{data.lab_pcr_nama}}</span>
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
                <td width="47%">
                  <b>Nomor Registrasi</b>
                </td>
                <td width="60%">
                  <span>{{data.nomor_register}}</span>
                </td>
              </tr>

              <tr>
                <td width="47%">
                  <b>Jenis Registrasi</b>
                </td>
                <td width="60%">
                  <span>{{data.register.janis_registrasi}}</span>
                </td>
              </tr>

              <tr>
                <td width="30%">
                  <b>Sumber Pasien</b>
                </td>
                <td width="60%">
                  <span>{{data.register.sumber_pasien}}</span>
                </td>
              </tr>

              <tr>
                <td width="30%">
                  <b>Fasyankes</b>
                </td>
                <td width="60%">
                  <span v-if="data.fasyankes">{{data.fasyankes.nama}}</span>
                </td>
              </tr>

              <tr>
                <td width="30%">
                  <b>Nama Pasien</b>
                </td>
                <td width="60%">
                  <span>{{data.pasien.nama_lengkap}}</span>
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>Nomor Induk Kependudukan</b>
                </td>
                <td width="60%">
                  <span>{{data.pasien.nik }}</span>
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>Tempat, Tanggal Lahir Pasien</b>
                </td>
                <td width="60%">
                  <span>{{ data.pasien.tempat_lahir }} {{data.pasien.tanggal_lahir | formatDate }} - {{ umurPasien }}</span>
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>Jenis Kelamin</b>
                </td>
                <td width="60%">
                  <span v-if="data.pasien.jenis_kelamin === 'L'">Laki - laki</span>
                  <span v-if="data.pasien.jenis_kelamin === 'P'">Perempuan</span>
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>Kabupaten/Kota</b>
                </td>
                <td width="60%">
                  <span v-if="data.pasien.kota">{{ data.pasien.kota.nama }}</span>
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>Kecamatan</b>
                </td>
                <td width="60%">
                  <span>{{ data.pasien.kecamatan }}</span>
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>Kelurahan</b>
                </td>
                <td width="60%">
                  <span>{{ data.pasien.kelurahan }}</span>
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>RT/RW</b>
                </td>
                <td width="60%">
                  <span>{{ data.pasien.no_rt }}</span>
                  <span v-if="data.pasien.no_rw">/</span>
                  <span>{{ data.pasien.no_rw }}</span>
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>Alamat</b>
                </td>
                <td width="60%">
                  <span>{{ data.pasien.alamat_lengkap }}</span>
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>No. Telp/HP</b>
                </td>
                <td width="60%">
                  <span v-if="data.pasien.no_hp">{{data.pasien.no_hp }}</span>
                  <span v-else-if="data.pasien.no_telp">{{data.pasien.no_telp }}</span>
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>Tanggal Registrasi</b>
                </td>
                <td width="60%">
                  <span>{{ data.register.created_at | formatDate }}</span>
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>Dinkes Pengirim</b>
                </td>
                <td width="60%">
                  <span>{{ data.register.dinkes_pengirim }}</span>
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>Tanggal Periksa / Lapor</b>
                </td>
                <td width="60%">
                  <span>{{ data.register.tanggal_kunjungan | formatDate }}</span>
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>Fasyankes</b>
                </td>
                <td width="60%">
                  <span v-if="data.fasyankes">{{ data.fasyankes.nama }}</span>
                  <span v-if="!data.fasyankes">{{ data.register.rs_kunjungan }}</span>
                </td>
              </tr>
              <tr>
                <td width="30%">
                  <b>Suhu</b>
                </td>
                <td width="60%">
                  <span>{{ data.pasien.suhu }} (°C)</span>
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
          <div class="col-md-6" v-for="hasil in data.pemeriksaan_sampel" :key="hasil.id">
            <Ibox title="Pemeriksaan Sampel">
              <table class="table">
                <tbody>
                  <tr>
                    <td width="47%">
                      <b>Tanggal Penerimaan Sampel</b>
                    </td>
                    <td>{{ hasil.tanggal_penerimaan_sampel | formatDate }}</td>
                  </tr>
                  <tr>
                    <td>
                      <b>Petugas Penerimaan Sampel RNA</b>
                    </td>
                    <td>{{ hasil.petugas_penerimaan_sampel_rna }}</td>
                  </tr>
                  <tr>
                    <td>
                      <b>Operator Realtime PCR</b>
                    </td>
                    <td>{{ hasil.operator_realtime_pcr }}</td>
                  </tr>
                  <tr>
                    <td>
                      <b>Tanggal Mulai Pemeriksaan</b>
                    </td>
                    <td>{{ hasil.tanggal_mulai_pemeriksaan | formatDate }}</td>
                  </tr>
                  <tr>
                    <td>
                      <b>Jam Mulai Pemeriksaan</b>
                    </td>
                    <td>{{ hasil.jam_mulai_pemeriksaan }}</td>
                  </tr>
                  <tr>
                    <td>
                      <b>Jam Selesai Pemeriksaan</b>
                    </td>
                    <td>{{ hasil.jam_selesai_pemeriksaan }}</td>
                  </tr>
                  <tr>
                    <td>
                      <b>Metode Pemeriksaan</b>
                    </td>
                    <td>
                      <span v-if="hasil.nama_kit_pemeriksaan">PCR - {{ hasil.nama_kit_pemeriksaan }}</span>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <b>Tanggal Input Hasil</b>
                    </td>
                    <td>{{ hasil.tanggal_input_hasil | formatDate }}</td>
                  </tr>
                  <tr>
                    <td>
                      <b>Jam Input Hasil</b>
                    </td>
                    <td>{{ hasil.jam_input_hasil }}</td>
                  </tr>
                  <tr>
                    <td>
                      <b>Kesimpulan Pemeriksaan</b>
                    </td>
                    <td>{{ hasil.kesimpulan_pemeriksaan }}</td>
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
                  <tr v-for="(hasil, $index) in hasil.hasil_deteksi" :key="$index">
                    <td>
                      <p class="form-control">
                        <b>{{ hasil.target_gen }}</b>
                      </p>
                    </td>
                    <td>
                      <p class="form-control">
                        <b>{{ hasil.ct_value }}</b>
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
  async asyncData({ route }) {
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

    return { data };
  },
  computed: {
    umurPasien() {
      if (this.data.pasien) {
        let tglLahir = new Date(this.data.pasien.tanggal_lahir);
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