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
        <Ibox :title="`Detail Register Pasien #${this.id}`">
          <template v-slot:tools>
            <button class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</button>
            <button class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i> Edit</button>
            <button class="btn btn-xs btn-primary"><i class="fa fa-info"></i> Detail</button>
          </template>
          <div class="container">
            <div class="row">
              <div class="col-md-6">
                <h3 class="header-title mt-2 mb-2">Identitas Pasien</h3>
                <table class="table">
                  <tbody>
                    <tr>
                      <td width="40%"><b>Nama Lengkap Pasien</b></td>
                      <td width="60%">{{data.reg_nama_pasien}}</td>
                    </tr>
                    <tr>
                      <td width="40%"><b>Nomor Induk Kependudukan</b></td>
                      <td width="60%"> {{data.reg_nik}} </td>
                    </tr>
                    <tr>
                      <td width="40%"><b>Nomor Surat Izin Mengemudi</b></td>
                      <td width="60%">{{data.reg_nosim}}</td>
                    </tr>
                    <tr>
                      <td width="40%"><b>Nomor Kartu Keluarga</b></td>
                      <td width="60%">{{data.reg_nkk}}</td>
                    </tr>
                    <tr>
                      <td width="40%"><b>Pekerjaan</b></td>
                      <td width="60%">{{data.reg_pekerjaan}}</td>
                    </tr>
                    <tr>
                      <td width="40%"><b>Tempat, Tanggal Lahir & Usia</b></td>
                      <td width="60%">{{data.reg_tempatlahir}},
                        {{data.reg_tanggallahir}}
                        ({{data.reg_usia}})</td>
                    </tr>
                    <tr>
                      <td width="40%"><b>Jenis Kelamin</b></td>
                      <td width="60%">
                        <p>{{data.reg_kelamin}}</p>
                        <p>{{data.reg_hamil_pasca}}</p>
                      </td>
                    </tr>
                    <tr>
                      <td width="40%"><b>Alamat Pasien</b></td>
                      <td width="60%">{{data.reg_alamat}} {{data.reg_domisilirt}}
                        {{data.reg_domisilirw}}, {{data.reg_domisilikotakab}},
                        {{data.reg_domisilikecamatan}}, {{data.reg_domisilikelurahan}}</td>
                    </tr>
                    <tr>
                      <td width="40%"><b>No Telp Pasien</b></td>
                      <td width="60%">{{data.reg_notelp_pasien}}</td>
                    </tr>
                  </tbody>
                </table>

              </div>
              <div class="col-md-6">

                <h3 class="header-title mt-2 mb-2">Identitas Pengirim Spesimen</h3>
                <table class="table">
                  <tbody>
                    <tr>
                      <td width="40%"><b>Dinkes Pengirim</b></td>
                      <td width="60%">{{data.reg_dinkes_pengirim}}</td>
                    </tr>
                    <tr>
                      <td width="40%"><b>Fasyankes Pengirim</b></td>
                      <td width="60%">{{data.reg_fasyankes_pengirim}}</td>
                    </tr>
                    <tr>
                      <td width="40%"><b>Nama Rumah Sakit / Fasyankes</b></td>
                      <td width="60%">
                        {{data.reg_nama_rs == 'Other'?data.reg_nama_rs_lainnya:data.reg_nama_rs}}
                      </td>
                    </tr>
                    <tr>
                      <td width="40%"><b>Nomor Rekam Medis</b></td>
                      <td width="60%">{{data.reg_no_rekammedis}}</td>
                    </tr>
                    <tr>
                      <td width="40%"><b>Nama Dokter Penanggung Jawab</b></td>
                      <td width="60%">{{data.reg_nama_dokter}}</td>
                    </tr>
                    <tr>
                      <td width="40%"><b>No Telp Fasyankes Pengirim</b></td>
                      <td width="60%">{{data.reg_telp_fas_pengirim}}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <hr>
            <h3 class="header-title mt-2 mb-2">Riwayat Perawatan Pasien</h3>
            <table class="table">
              <thead>
                <tr>
                  <th>Kunjungan Ke</th>
                  <th>Tanggal Dirawat</th>
                  <th>Rumah Sakit / Fasyankes</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td width="10%">{{data.reg_kunke}}</td>
                  <td width="30%">{{data.reg_tanggalkunjungan}}</td>
                  <td width="60%">{{data.reg_rsfasyankes}}</td>
                </tr>

              </tbody>
            </table>
            <hr>
            <h3 class="header-title mt-2 mb-2">Tanda dan Gejala</h3>
            <table class="table">
              <tbody>
                <tr>
                  <td width="40%"><b>Tanggal onset gejala (panas)</b></td>
                  <td width="60%">{{data.reg_onset_panas}}</td>
                </tr>
                <tr>
                  <td width="40%"><i>Gejala klinis saat spesimen diambil :</i></td>
                </tr>
                <tr>
                  <td width="40%"><b>Panas atau riwayat panas >= 38 C</b></td>
                  <td width="60%">{{data.reg_gejpanas}}</td>
                </tr>
                <tr>
                <tr>
                  <td width="40%"><b>Tanda Pneumonia</b></td>
                  <td width="60%">{{data.reg_gejpenumonia}}</td>
                </tr>
                <tr>
                  <td width="40%"><b>Batuk</b></td>
                  <td width="60%">{{data.reg_gejbatuk}}</td>
                </tr>
                <tr>
                  <td width="40%"><b>Nyeri Tenggorokan</b></td>
                  <td width="60%">{{data.reg_gejnyeritenggorokan}}</td>
                </tr>
                <tr>
                  <td width="40%"><b>Sesak Nafas</b></td>
                  <td width="60%">{{data.reg_gejsesaknafas}}</td>
                </tr>
                <tr>
                  <td width="40%"><b>Pilek</b></td>
                  <td width="60%">{{data.reg_gejpilek}}</td>
                </tr>
                <tr>
                  <td width="40%"><b>Lesu</b></td>
                  <td width="60%">{{data.reg_gejlesu}}</td>
                </tr>
                <tr>
                  <td width="40%"><b>Sakit Kepala</b></td>
                  <td width="60%">{{data.reg_gejsakitkepala}}</td>
                </tr>
                <tr>
                  <td width="40%"><b>Diare</b></td>
                  <td width="60%">{{data.reg_gejdiare}}</td>
                </tr>
                <tr>
                  <td width="40%"><b>Mual / Muntah</b></td>
                  <td width="60%">{{data.reg_gejmualmuntah}}</td>
                </tr>
                <tr>
                  <td width="40%"><b>Gejala Lainnya</b></td>
                  <td width="60%">{{data.reg_gejlain}}</td>
                </tr>
              </tbody>
            </table>

            <hr>
            <h3 class="header-title mt-2 mb-2">Pemeriksaan Penunjang</h3>
            <table class="table">
              <tbody>
                <tr>
                  <td width="40%"><b>X-Ray Paru</b></td>
                  <td width="60%">{{data.reg_xrayparu}}</td>
                </tr>
                <tr>
                  <td width="40%"><b>Hasil X-Ray Paru</b></td>
                  <td width="60%">{{data.reg_hasilxray}}</td>
                </tr>
                <tr>
                  <td width="40%"><i>Hitung sel darah putih</i></td>
                </tr>
                <tr>
                  <td width="40%"><b>Leukosit</b></td>
                  <td width="60%">{{data.reg_leukosit}} /ul</td>
                </tr>
                <tr>
                  <td width="40%"><b>Limfosit</b></td>
                  <td width="60%">{{data.reg_limfosit}} /ul</td>
                </tr>
                <tr>
                  <td width="40%"><b>Trombosit</b></td>
                  <td width="60%">{{data.reg_trombosit}} /ul</td>
                </tr>
                <tr>
                  <td width="40%"><b>Menggunakan Ventilator</b></td>
                  <td width="60%">{{data.reg_ventilator}}</td>
                </tr>
                <tr>
                  <td width="40%"><b>Status Kesehatan Pasien</b></td>
                  <td width="60%">{{data.reg_statuskes}}</td>
                </tr>

                <tr>
                  <td width="40%"><b>Hasil Lab Lainnya</b></td>
                  <td width="60%">{{data.reg_hasilllablainya}}</td>
                </tr>
              </tbody>
            </table>

            <hr>
            <h3 class="header-title mt-2 mb-2">Riwayat Kunjungan Luar Negri</h3>
            <table class="table">
              <tbody>
                <tr>
                  <td width="40%"><b>Dalam 14 hari sebelum sakit, apakah pasien melakukan perjalanan
                      ke luar negeri?</b></td>
                  <td width="60%">{{data.reg_luarnegri}}</td>
                </tr>

              </tbody>
            </table>
            <table class="table">
              <thead>
                <tr>
                  <th>Tanggal Kunjungan</th>
                  <th>Kota</th>
                  <th>Negara</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>{{data.reg_tanggalkunjungan_pasien1}}</td>
                  <td>{{data.reg_kotakunjungan_pasien1}}</td>
                  <td>{{data.reg_negarakunjungan_pasien1}}</td>
                </tr>
                <tr>
                  <td>{{data.reg_tanggalkunjungan_pasien2}}</td>
                  <td>{{data.reg_kotakunjungan_pasien2}}</td>
                  <td>{{data.reg_negarakunjungan_pasien2}}</td>
                </tr>
                <tr>
                  <td>{{data.reg_tanggalkunjungan_pasien3}}</td>
                  <td>{{data.reg_kotakunjungan_pasien3}}</td>
                  <td>{{data.reg_negarakunjungan_pasien3}}</td>
                </tr>
                <tr>
                  <td>{{data.reg_tanggalkunjungan_pasien4}}</td>
                  <td>{{data.reg_kotakunjungan_pasien4}}</td>
                  <td>{{data.reg_negarakunjungan_pasien4}}</td>
                </tr>
                <tr>
                  <td>{{data.reg_tanggalkunjungan_pasien5}}</td>
                  <td>{{data.reg_kotakunjungan_pasien5}}</td>
                  <td>{{data.reg_negarakunjungan_pasien5}}</td>
                </tr>
                <tr>
                  <td>{{data.reg_tanggalkunjungan_pasien6}}</td>
                  <td>{{data.reg_kotakunjungan_pasien6}}</td>
                  <td>{{data.reg_negarakunjungan_pasien6}}</td>
                </tr>
                <tr>
                  <td>{{data.reg_tanggalkunjungan_pasien7}}</td>
                  <td>{{data.reg_kotakunjungan_pasien7}}</td>
                  <td>{{data.reg_negarakunjungan_pasien7}}</td>
                </tr>
              </tbody>
            </table>
            <br>
            <table class="table">
              <tbody>
                <tr>
                  <td width="40%"><b>Dalam 14 hari sebelum sakit, apakah pasien kontak dengan orang
                      yang sakit?</b></td>
                  <td width="60%">{{data.reg_kontakdgnsakit}}</td>
                </tr>
              </tbody>
            </table>
            <br>
            <table class="table">
              <thead>
                <tr>
                  <th>Nama</th>
                  <th>Alamat</th>
                  <th>Hubungan</th>
                  <th>Kontak Pertama</th>
                  <th>Kontak Terakhir</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>{{data.reg_namakon1}}</td>
                  <td>{{data.reg_alamatkon1}}</td>
                  <td>{{data.reg_hubungankon1}}</td>
                  <td>{{data.reg_tanggalkonawal1}}</td>
                  <td>{{data.reg_tanggalkonakhir1}}</td>
                </tr>
                <tr>
                  <td>{{data.reg_namakon2}}</td>
                  <td>{{data.reg_alamatkon2}}</td>
                  <td>{{data.reg_hubungankon2}}</td>
                  <td>{{data.reg_tanggalkonawal2}}</td>
                  <td>{{data.reg_tanggalkonakhir2}}</td>
                </tr>
                <tr>
                  <td>{{data.reg_namakon3}}</td>
                  <td>{{data.reg_alamatkon3}}</td>
                  <td>{{data.reg_hubungankon3}}</td>
                  <td>{{data.reg_tanggalkonawal3}}</td>
                  <td>{{data.reg_tanggalkonakhir3}}</td>
                </tr>
                <tr>
                  <td>{{data.reg_namakon4}}</td>
                  <td>{{data.reg_alamatkon4}}</td>
                  <td>{{data.reg_hubungankon4}}</td>
                  <td>{{data.reg_tanggalkonawal4}}</td>
                  <td>{{data.reg_tanggalkonakhir4}}</td>
                </tr>
                <tr>
                  <td>{{data.reg_namakon5}}</td>
                  <td>{{data.reg_alamatkon5}}</td>
                  <td>{{data.reg_hubungankon5}}</td>
                  <td>{{data.reg_tanggalkonawal5}}</td>
                  <td>{{data.reg_tanggalkonakhir5}}</td>
                </tr>
                <tr>
                  <td>{{data.reg_namakon6}}</td>
                  <td>{{data.reg_alamatkon6}}</td>
                  <td>{{data.reg_hubungankon6}}</td>
                  <td>{{data.reg_tanggalkonawal6}}</td>
                  <td>{{data.reg_tanggalkonakhir6}}</td>
                </tr>
                <tr>
                  <td>{{data.reg_namakon7}}</td>
                  <td>{{data.reg_alamatkon7}}</td>
                  <td>{{data.reg_hubungankon7}}</td>
                  <td>{{data.reg_tanggalkonawal7}}</td>
                  <td>{{data.reg_tanggalkonakhir7}}</td>
                </tr>

              </tbody>
            </table>
            <br>
            <table class="table">
              <tbody>
                <tr>
                  <td width="40%"><b>Apakah orang tersebut tersangka/terinfeksi Covid-19?</b></td>
                  <td width="60%">{{data.reg_kontakterinfeksi}}</td>
                </tr>
                <tr>
                  <td width="40%"><b>Apakah ada anggota keluarga pasien yang sakitnya sama?</b></td>
                  <td width="60%">{{data.reg_keluargapasiensakitsama}}</td>
                </tr>
              </tbody>
            </table>
            <hr>
            <h3 class="header-title mt-2 mb-2">Komorbid Pasien</h3>
            <table class="table">
              <tbody>
                <tr>
                  <td width="40%"><b>Penyakit Kardiovaskuler/Hipertensi</b></td>
                  <td width="60%">{{data.reg_komorbidhipertensi}}</td>
                </tr>
                <tr>
                  <td width="40%"><b>Diabetes Mellitus</b></td>
                  <td width="60%">{{data.reg_komorbiddm}}</td>
                </tr>
                <tr>
                  <td width="40%"><b>Liver</b></td>
                  <td width="60%">{{data.reg_komorbidliver}}</td>
                </tr>
                <tr>
                  <td width="40%"><b>Kronik neurologi / neuromuskula</b></td>
                  <td width="60%">{{data.reg_komorbidneurologi}}</td>
                </tr>
                <tr>
                  <td width="40%"><b>Imunodefisiensi / HIV</b></td>
                  <td width="60%">{{data.reg_komorbidhiv}}</td>
                </tr>
                <tr>
                  <td width="40%"><b>Penyakit paru kronik</b></td>
                  <td width="60%">{{data.reg_komorbidparu}}</td>
                </tr>
                <tr>
                  <td width="40%"><b>Penyakit Ginjal</b></td>
                  <td width="60%">{{data.reg_komorbidginjal}}</td>
                </tr>

                <tr>
                  <td width="40%"><b>Penjelasan Lain</b></td>
                  <td width="60%">{{data.reg_penjelasanlain}}</td>
                </tr>
              </tbody>
            </table>
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

  export default {
    middleware: 'auth',
    data() {
      return {
        params: {
          nama: null
        },
      }
    },
    computed: {
      ...mapState('register', {
        id: state => state.id,
        data: state => state.data
      })
    },
    head() {
      return {
        title: this.$t('home')
      }
    }
  }
</script>