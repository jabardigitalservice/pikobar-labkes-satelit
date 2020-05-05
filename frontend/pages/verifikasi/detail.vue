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
      <div class="col-lg-12">
        <Ibox title="Informasi Sampel">
          <div class="row">
            <div class="col-md-12">
              <table class="table">
                <tbody>
                  <tr>
                    <td width="47%" align="right">
                      <b>Nomor Registrasi</b>
                    </td>
                    <td width="60%">
                      <span>{{data.nomor_register}}</span>
                    </td>
                  </tr>

                  <tr>
                    <td width="30%" align="right">
                      <b>Nomor Sampel</b>
                    </td>
                    <td width="60%">
                      <span>{{data.nomor_sampel}}</span>
                    </td>
                  </tr>

                  <tr>
                    <td width="30%" align="right">
                      <b>Sumber Pasien</b>
                    </td>
                    <td width="60%">
                      <span>{{data.register.sumber_pasien}}</span>
                    </td>
                  </tr>

                  <tr>
                    <td width="30%" align="right">
                      <b>Fasyankes</b>
                    </td>
                    <td width="60%">
                      <span v-if="data.fasyankes">{{data.fasyankes.nama}}</span>
                    </td>
                  </tr>

                  <tr>
                    <td width="30%" align="right">
                      <b>Pasien</b>
                    </td>
                    <td width="60%">
                      <span>{{data.pasien.nama_lengkap}}</span>
                    </td>
                  </tr>
                  <tr>
                    <td width="30%" align="right">
                      <b>Nomor Induk Kependudukan</b>
                    </td>
                    <td width="60%">
                      <span>{{data.pasien.nik }}</span>
                    </td>
                  </tr>
                  <tr>
                    <td width="30%" align="right">
                      <b>Tempat, Tanggal Lahir Pasien</b>
                    </td>
                    <td width="60%">
                      <span>{{ data.pasien.tempat_lahir }} {{data.pasien.tanggal_lahir | formatDate }} - {{ umurPasien }}</span>
                    </td>
                  </tr>
                  <tr>
                    <td width="30%" align="right">
                      <b>Jenis Kelamin</b>
                    </td>
                    <td width="60%">
                      <span v-if="data.pasien.jenis_kelamin === 'L'">Laki - laki</span>
                      <span v-if="data.pasien.jenis_kelamin === 'P'">Perempuan</span>
                    </td>
                  </tr>
                  <tr>
                    <td width="30%" align="right">
                      <b>Asal</b>
                    </td>
                    <td width="60%">
                      <span v-if="data.pasien.kota">{{ data.pasien.kota.nama }}</span>
                    </td>
                  </tr>
                  <tr>
                    <td width="30%" align="right">
                      <b>Alamat</b>
                    </td>
                    <td width="60%">
                      <span>{{ data.pasien.alamat_lengkap }}</span>
                    </td>
                  </tr>
                  <tr>
                    <td width="30%" align="right">
                      <b>No. Telp/HP</b>
                    </td>
                    <td width="60%">
                      <span v-if="data.pasien.no_hp">{{data.pasien.no_hp }}</span>
                      <span v-else-if="data.pasien.no_telp">{{data.pasien.no_telp }}</span>
                    </td>
                  </tr>
                  <tr>
                    <td width="30%" align="right">
                      <b>Tipe Sampel</b>
                    </td>
                    <td width="60%">
                      <span>{{ data.jenis_sampel }}</span>
                    </td>
                  </tr>
                  <tr>
                    <td width="30%" align="right">
                      <b>Tanggal Registrasi</b>
                    </td>
                    <td width="60%">
                      <span>{{ data.register.created_at | formatDate }}</span>
                    </td>
                  </tr>
                  <tr>
                    <td width="30%" align="right">
                      <b>Tanggal Periksa / Lapor</b>
                    </td>
                    <td width="60%">
                      <span>{{ data.register.tanggal_kunjungan | formatDate }}</span>
                    </td>
                  </tr>

                  <tr>
                    <td width="30%" align="right">
                      <b>Lab Penerima</b>
                    </td>
                    <td width="60%">
                      <span>{{data.lab_pcr_nama}}</span>
                    </td>
                  </tr>
                </tbody>
              </table>

              <hr />

              <div class="row">
                <div class="col-md-6" 
                  v-for="hasil in data.pemeriksaan_sampel" 
                  :key="hasil.id"
                >
                  <Ibox title="Pemeriksaan Sampel">

                    <div class="form-group">
                      <label>Tanggal Input Hasil</label>
                      <p class="form-control">
                        <b>
                          {{ hasil.tanggal_input_hasil | formatDate }}
                        </b>
                      </p>
                    </div>

                    <div class="form-group">
                      <label>Jam Input Hasil</label>
                      <p class="form-control">
                        <b>
                          {{ hasil.jam_input_hasil }}
                        </b>
                      </p>
                    </div>

                    <div class="form-group">
                      <label>Tanggal Mulai Pemeriksaan</label>
                      <p class="form-control">
                        <b>{{ hasil.tanggal_mulai_pemeriksaan | formatDate }}</b>
                      </p>
                    </div>

                    <div class="form-group">
                      <label>Metode Pemeriksaan</label>
                      <p class="form-control">
                        <b>{{ hasil.metode_pemeriksaan }}</b>
                      </p>
                    </div>

                    <div class="form-group">
                      <label>Kesimpulan Pemeriksaan</label>
                      <p class="form-control">
                        <b>{{ hasil.kesimpulan_pemeriksaan }}</b>
                      </p>
                    </div>

                    <div class="form-group">
                      <label>Catatan Pemeriksaan</label>
                      <p class="form-control">
                        <b>{{ hasil.catatan_pemeriksaan }}</b>
                      </p>
                    </div>

                    <table class="table table-striped dt-responsive table-bordered" style="width:100%">
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
                              <b>
                                {{ hasil.target_gen }}
                              </b>
                            </p>
                          </td>
                          <td>
                            <p class="form-control">
                              <b>
                                {{ hasil.ct_value }}
                              </b>
                            </p>
                          </td>
                        </tr>
                      </tbody>
                    </table>

                    

                  </Ibox>
                </div>
              </div>
              
            </div>
          </div>
        </Ibox>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  middleware: "auth",
  async asyncData({route}) {
    let resp = await axios.get("/v1/verifikasi/detail/" + route.params.id);
    let data = resp.data.data

    if (!data.pasien) {
      data.pasien = {}
    }

    return { data };
  },
  computed: {

    umurPasien(){
      if (this.data.pasien) {
        let tglLahir = new Date(this.data.pasien.tanggal_lahir);
            let today_date = new Date();
            let today_year = today_date.getFullYear();
            let today_month = today_date.getMonth();
            let today_day = today_date.getDate();

            var age = today_date.getFullYear() - tglLahir.getFullYear();
            var m = today_date.getMonth() - tglLahir.getMonth();
            if (m < 0 || (m === 0 && today_date.getDate() < tglLahir.getDate())) 
            {
                age--;
            }

            return `Usia: ${age} tahun`
      }

      return '';
    }

  },
  head() {
    return {
      title: "Detil Sampel Hasil Pemeriksaan"
    };
  }
};
</script>