<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Registrasi Sampel
    </portal>
    <portal to="title-action">
      <div class="title-action">
        <nuxt-link to="/registrasi/perujuk" class="btn btn-black">
          <i class="uil-arrow-left" /> Kembali
        </nuxt-link>
      </div>
    </portal>

    <div class="row">
      <div class="col-lg-12">
        <form @submit.prevent="submit" @keydown="form.onKeydown($event)">
          <Ibox title="Identitas Pengirim">
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Instansi Pengirim
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8" :class="{ 'is-invalid': form.errors.has('fasyankes_pengirim') }">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="fasyankes_pengirim" value="rumah_sakit"
                    v-model="form.fasyankes_pengirim" required>
                  <label class="form-check-label" for="fasyanrs">Rumah Sakit</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="fasyankes_pengirim" value="dinkes"
                    v-model="form.fasyankes_pengirim" required>
                  <label class="form-check-label" for="fasyandinkes">Dinkes</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="fasyankes_pengirim" value="puskesmas"
                    v-model="form.fasyankes_pengirim" required>
                  <label class="form-check-label" for="fasyandinkes">Puskesmas</label>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Nama Fasyankes
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <multiselect v-model="fasyankes" :options="optionFasyankes" track-by="nama" label="nama" required
                  placeholder="Nama Rumah Sakit/Dinkes" :class="{ 'is-invalid': form.errors.has('fasyankes_id') }">
                </multiselect>
                <has-error :form="form" field="fasyankes_id" />
              </div>
            </div>
          </Ibox>

          <Ibox title="Identitas Pasien">
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Kode Kasus
              </div>
              <div class="col-md-8">
                <input class="form-control" type="text" name="kode_kasus" placeholder="Kode kasus"
                  v-model="form.kode_kasus" :class="{ 'is-invalid': form.errors.has('kode_kasus') }" />
                <has-error :form="form" field="kode_kasus" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Nama Pasien
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <input class="form-control" type="text" name="nama_pasien" placeholder="Nama Pasien" required
                  v-model="form.nama_pasien" :class="{ 'is-invalid': form.errors.has('nama_pasien') }" />
                <has-error :form="form" field="nama_pasien" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                NIK
              </div>
              <div class="col-md-8">
                <input class="form-control" type="text" name="nik" placeholder="NIK" v-model="form.nik" maxlength="16"
                  :class="{ 'is-invalid': form.errors.has('nik') }" />
                <has-error :form="form" field="nik" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Tempat Lahir
              </div>
              <div class="col-md-8">
                <input class="form-control" type="text" name="tempat_lahir" placeholder="Tempat Lahir"
                  v-model="form.tempat_lahir" :class="{ 'is-invalid': form.errors.has('tempat_lahir') }" />
                <has-error :form="form" field="tempat_lahir" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Tanggal Lahir
              </div>
              <div class="col-md-8">
                <date-picker placeholder="Tanggal Lahir" format="dd MMMM yyyy" ref="tgl_lahir"
                  input-class="form-control" :monday-first="true" v-model="form.tanggal_lahir"
                  :class="{ 'is-invalid': form.errors.has('tanggal_lahir') }" />
                <has-error :form="form" field="tanggal_lahir" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Usia
              </div>
              <div class="col-md-3 col-lg-2">
                <input class="form-control" type="number" name="usia_tahun" placeholder="Tahun"
                  v-model="form.usia_tahun" :class="{ 'is-invalid': form.errors.has('usia_tahun') }" />
                <has-error :form="form" field="usia_tahun" />
              </div>
              <div class="col-md-3 col-lg-2">
                <input class="form-control" type="number" name="usia_bulan" placeholder="Bulan"
                  v-model="form.usia_bulan" :class="{ 'is-invalid': form.errors.has('usia_bulan') }" />
                <has-error :form="form" field="usia_bulan" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Jenis Kelamin
              </div>
              <div class="col-md-8">
                <div class="form-check form-check-inline" :class="{ 'is-invalid': form.errors.has('jenis_kelamin') }">
                  <label class="fancy-radio custom-color-green m-0 w-100">
                    <input v-model="form.jenis_kelamin" value="L" type="radio">
                    <span><i />Laki-laki</span>
                  </label>
                </div>
                <div class="form-check form-check-inline">
                  <label class="fancy-radio custom-color-green m-0 w-100">
                    <input v-model="form.jenis_kelamin" value="P" type="radio">
                    <span><i />Perempuan</span>
                  </label>
                </div>
                <has-error :form="form" field="jenis_kelamin" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                No. Telp / HP
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <input class="form-control" type="text" name="no_hp" placeholder="Nomor Telepon / HP"
                  v-model="form.no_hp" :class="{ 'is-invalid': form.errors.has('no_hp') }" required />
                <has-error :form="form" field="no_hp" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Alamat
              </div>
              <div class="col-md-8">
                <textarea class="multisteps-form__input form-control" type="text" name="alamat" placeholder="Alamat"
                  v-model="form.alamat" :class="{ 'is-invalid': form.errors.has('alamat') }"></textarea>
                <has-error :form="form" field="alamat" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                RT / RW
              </div>
              <div class="input-group col-md-3 col-lg-2 flex-text-center">
                <input class="form-control" placeholder="RT" readonly="readonly" />
                <input class="form-control" type="text" name="no_rt" v-model="form.no_rt"
                  :class="{ 'is-invalid':form.errors.has('no_rt') }">
              </div>
              <div class="input-group col-md-3 col-lg-2 flex-text-center">
                <input class="form-control" placeholder="RW" readonly="readonly" />
                <input class="form-control" type="text" name="no_rw" v-model="form.no_rw"
                  :class="{ 'is-invalid':form.errors.has('no_rw') }" />
              </div>
              <has-error :form="form" field="no_rt" />
              <has-error :form="form" field="no_rw" />
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Provinsi
              </div>
              <div class="col-md-8">
                <multiselect v-model="provinsi" :options="optionProvinsi" track-by="nama" label="nama"
                  placeholder="Pilih Provinsi" :class="{ 'is-invalid': form.errors.has('provinsi') }">
                </multiselect>
                <has-error :form="form" field="provinsi" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Kota / Kabupaten
              </div>
              <div class="col-md-8">
                <multiselect v-model="kota" :options="optionKota" track-by="nama" label="nama"
                  placeholder="Pilih Kota / Kabupaten" :loading="isLoadingKota" :searchable="true"
                  :class="{ 'is-invalid': form.errors.has('kota_id') }">
                </multiselect>
                <has-error :form="form" field="kota_id" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Kecamatan
              </div>
              <div class="col-md-8">
                <multiselect v-model="kecamatan" :options="optionKecamatan" track-by="nama" label="nama"
                  placeholder="Pilih Kecamatan" :loading="isLoadingKecamatan" :searchable="true"
                  :class="{ 'is-invalid': form.errors.has('kecamatan_id') }">
                </multiselect>
                <has-error :form="form" field="kecamatan_id" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Kelurahan / Desa
              </div>
              <div class="col-md-8">
                <multiselect v-model="kelurahan" :options="optionKelurahan" track-by="nama" label="nama"
                  placeholder="Pilih Kelurahan / Desa" :loading="isLoadingKelurahan" :searchable="true"
                  :class="{ 'is-invalid': form.errors.has('kelurahan_id') }">
                </multiselect>
                <has-error :form="form" field="kelurahan_id" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Kewarganegaraan
              </div>
              <div class="col-md-8">
                <select v-model="form.kewarganegaraan" class="multisteps-form__input form-control col-md-8 col-lg-6"
                  name="kewarganegaraan" :class="{ 'is-invalid': form.errors.has('kewarganegaraan') }">
                  <option value="WNI">WNI</option>
                  <option value="WNA">WNA</option>
                </select>
                <has-error :form="form" field="kewarganegaraan" />
                <multiselect v-if="form.kewarganegaraan === 'WNA'" v-model="country" :options="optionCountry"
                  track-by="nama" label="nama" placeholder="Pilih Negara" class="mt-3"
                  :class="{ 'is-invalid': form.errors.has('keterangan_warganegara') }">
                </multiselect>
                <has-error :form="form" field="keterangan_warganegara" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Kriteria Pasien
              </div>
              <div class="col-md-8">
                <select v-model="form.kriteria" class="multisteps-form__input form-control col-md-8 col-lg-6"
                  name="kriteria" :class="{ 'is-invalid': form.errors.has('kriteria') }">
                  <option v-for="index in pasien_status_option" v-bind:key="index.value" :value="index.value">
                    {{index.text}}
                  </option>
                </select>
                <has-error :form="form" field="kriteria" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Kategori
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <input class="multisteps-form__input form-control" type="text" name="sumber_pasien" required
                  placeholder="Kategori" v-model="form.sumber_pasien"
                  :class="{ 'is-invalid': form.errors.has('sumber_pasien') }" />
                <has-error :form="form" field="sumber_pasien" />
              </div>
            </div>
          </Ibox>

          <Ibox title="Identitas Sampel">
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Lab
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <select v-model="form.lab_satelit_id" class="form-control col-md-8 col-lg-6" name="lab_satelit_id"
                  required :class="{ 'is-invalid': form.errors.has(`lab_satelit_id`) }">
                  <option :value="item.id" :key="idx" v-for="(item, idx) in option_lab_satelit">
                    {{ item.nama }}
                  </option>
                </select>
                <has-error :form="form" field="lab_satelit_id" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Swab Ke
              </div>
              <div class="col-md-8">
                <input class="multisteps-form__input form-control" type="number" name="swab_ke" v-model="form.swab_ke"
                  :class="{ 'is-invalid': form.errors.has('swab_ke') }" />
                <has-error :form="form" field="swab_ke" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Tanggal Swab
              </div>
              <div class="col-md-8">
                <date-picker format="d MMMM yyyy" input-class="multisteps-form__input form-control" :monday-first="true"
                  v-model="form.tanggal_swab" :class="{ 'is-invalid': form.errors.has('tanggal_swab') }" />
                <has-error :form="form" field="tanggal_swab" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Jenis Sampel
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <select class="form-control" v-model="form.jenis_sampel" required
                  :class="{ 'is-invalid': form.errors.has(`jenis_sampel`) }">
                  <option :value="item.id" v-for="(item, $key) in jenis_sampel" :key="$key">
                    {{ item.text }}</option>
                </select>
                <has-error :form="form" :field="`jenis_sampel`" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Nomor sampel
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <input class="multisteps-form__input form-control" type="text" name="nomor_sampel" required
                  v-model="form.nomor_sampel" :class="{ 'is-invalid': form.errors.has('nomor_sampel') }" />
                <has-error :form="form" field="nomor_sampel" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Keterangan Lainnya
              </div>
              <div class="col-md-8">
                <textarea class="form-control" type="text" name="keterangan" v-model="form.keterangan" rows="6"
                  :class="{ 'is-invalid': form.errors.has('keterangan') }" />
                <has-error :form="form" field="keterangan" />
              </div>
            </div>
          </Ibox>
          
          <div class="col-md-12 mb-4">
            <button :loading="form.busy" class="btn btn-md btn-primary">
              <i class="fa fa-check" /> Simpan Data Register
            </button>
            <nuxt-link to="/registrasi/perujuk" class="btn btn-clear">
              <i class="fa fa-close" /> Batal
            </nuxt-link>
          </div>
          
        </form>
      </div>
    </div>
  </div>
</template>

<script>
  import Form from "vform";
  import axios from "axios";
  import moment from 'moment';
  import {
    mapGetters
  } from "vuex";
  import {
    pasienStatus
  } from '~/assets/js/constant/enum';
  export default {
    middleware: "auth",
    async asyncData({
      route,
      store
    }) {
      if (!store.getters["options/jenis_sampel"].length) {
        await store.dispatch("options/fetchJenisSampel");
      }
      let resp = await axios.get(`/v1/register-perujuk/detail/${route.params.id}`);
      let data = resp.data.result;
      let fasyankes = await axios.get('/v1/list-fasyankes-jabar?tipe=' + data.fasyankes_pengirim)
      let provinsi = await axios.get('/v1/list-provinsi/');
      provinsi = provinsi.data
      const dataProvinsi = provinsi ? provinsi.find((el) => el.id === data.provinsi_id) : null;
      let kota = data.provinsi_id ? await axios.get('/v1/list-kota/' + data.provinsi_id) : [];
      if (data.provinsi_id) {
        kota = kota.data
      }
      let kecamatan = data.kota_id ? await axios.get('/v1/list-kecamatan/' + data.kota_id) : [];
      if (data.kota_id) { 
        kecamatan = kecamatan.data
      }
      const dataKecamatan = kecamatan ? kecamatan.find((el) => el.id === data.kecamatan_id) : null;
      let kelurahan = data.kecamatan_id ? await axios.get('/v1/list-kelurahan/' + data.kecamatan_id) : [];
      if (data.kecamatan_id) {
        kelurahan = kelurahan.data
      }
      const dataKelurahan = kelurahan ? kelurahan.find((el) => el.id === data.kelurahan_id) : null;
      return {
        form: new Form({
          kode_kasus: data.kode_kasus,
          fasyankes_id: data.fasyankes ? data.fasyankes.id : data.fasyankes_id,
          fasyankes_pengirim: data.fasyankes ? data.fasyankes.tipe : data.fasyankes_pengirim,
          nama_rs: data.nama_rs,
          kewarganegaraan: data.kewarganegaraan,
          keterangan_warganegara: data.keterangan_warganegara,
          nama_pasien: data.nama_pasien,
          nik: data.nik,
          tempat_lahir: data.tempat_lahir,
          tanggal_lahir: data.tanggal_lahir,
          no_hp: data.no_hp,
          alamat: data.alamat,
          no_rt: data.no_rt,
          no_rw: data.no_rw,
          jenis_kelamin: data.jenis_kelamin,
          usia_tahun: data.usia_tahun,
          usia_bulan: data.usia_bulan,
          sampel_namadiluarjenis: data.sampel_namadiluarjenis,
          jenis_sampel: data.jenis_sampel,
          sampel_nomor: data.sampel_nomor,
          status: data.status,
          swab_ke: data.swab_ke,
          tanggal_swab: data.tanggal_swab,
          sumber_pasien: data.sumber_pasien,
          pelaporan_id: data.pelaporan_id,
          pelaporan_id_case: data.pelaporan_id_case,
          provinsi_id: data.provinsi_id,
          nama_provinsi: dataProvinsi ? dataProvinsi.nama : '',
          kota_id: data.kota_id,
          nama_kota: data.nama_kota,
          kecamatan_id: data.kecamatan_id,
          nama_kecamatan: data.nama_kecamatan,
          kelurahan_id: data.kelurahan_id,
          nama_kelurahan: data.nama_kelurahan,
          keterangan: data.keterangan,
          register: data.register,
          pasien: data.pasien,
          sampel_id: data.sampel_id,
          kriteria: data.kriteria,
          lab_satelit_id: data.lab_satelit_id,
          nomor_sampel: data.nomor_sampel
        }),
        optionFasyankes: fasyankes.data,
        optionCountry: [],
        optionProvinsi: provinsi,
        optionKecamatan: kecamatan,
        optionKelurahan: kelurahan,
        optionKota: kota,
        optionPencarian: [],
        fasyankes: {'id': data.fasyankes.id, 'nama': data.fasyankes.nama},
        country: data.keterangan_warganegara || null,
        provinsi: dataProvinsi ? {'id': dataProvinsi.id, 'nama': dataProvinsi.nama } : null,
        kota: data.kota ? {'id': data.kota.id, 'nama': data.kota.nama } : null,
        kecamatan: dataKecamatan ? {'id': dataKecamatan.id, 'nama': dataKecamatan.nama } : null,
        kelurahan: dataKelurahan ? {'id': dataKelurahan.id, 'nama': dataKelurahan.nama } : null,
        pencarian: null,
        pelaporan: null,
        isLoadingPencarian: false,
        isLoadingKota: false,
        isLoadingKecamatan: false,
        isLoadingKelurahan: false,
        pasien_status_option: pasienStatus,
        option_lab_satelit: null,
      };
    },
    methods: {
      async getLabSatelit() {
        const resp = await this.$axios.get("/lab-satelit");
        this.option_lab_satelit = resp.data.data;
      },
      async asyncFind(query) {
        this.isLoadingPencarian = true
        if (query != '') {
          let resp = await axios.get('/v1/pelaporan/fetch?keyword=' + query);
          this.optionPencarian = resp.data.data.content
          this.isLoadingPencarian = false
        }
      },
      async changeFasyankes(tipe) {
        let resp = await axios.get('/v1/list-fasyankes-jabar?tipe=' + tipe)
        this.optionFasyankes = resp.data;
      },
      async getCountry() {
        let resp = await this.$axios.get('/v1/list-negara/');
        this.optionCountry = resp.data;
        this.country = this.country ? this.optionCountry.find(el => el.nama === this.country) : null;
      },
      async getProvinsi() {
        let resp = await axios.get('/v1/list-provinsi/');
        this.optionProvinsi = resp.data;
      },
      async getKota(provinsi) {
        this.isLoadingKota = true;
        if (provinsi) {
          let resp = await axios.get('/v1/list-kota/' + provinsi);
          this.optionKota = resp.data;
          this.isLoadingKota = false;
        } else {
          this.isLoadingKota = false;
        }
      },
      async getKecamatan(kabupaten) {
        this.isLoadingKecamatan = true;
        if (kabupaten) {
          let resp = await axios.get('/v1/list-kecamatan/' + kabupaten);
          this.optionKecamatan = resp.data;
          this.isLoadingKecamatan = false;
        } else {
          this.isLoadingKecamatan = false;
        }
      },
      async getKelurahan(kecamatan) {
        this.isLoadingKelurahan = true;
        if (kecamatan) {
          let resp = await axios.get('/v1/list-kelurahan/' + kecamatan);
          this.optionKelurahan = resp.data;
          this.isLoadingKelurahan = false;
        } else {
          this.isLoadingKelurahan = false;
        }
      },
      async submit() {
        try {
          const response = await this.form.post(`/v1/register-perujuk/update/${this.$route.params.id}`);
          this.$swal.fire(
            "Berhasil Ubah Data",
            "Data Pasien Register Berhasil Diubah",
            "success"
          );
          this.$router.push("/registrasi/perujuk");
        } catch (err) {
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
          return;
        }
      },
    },
    head() {
      return {
        title: "Pengambilan / Penerimaan Sampel Baru"
      };
    },
    created() {
      this.getCountry();
      this.getProvinsi();
      this.getLabSatelit();
    },
    watch: {
      "form.nik": function (newVal, oldVal) {
        if (newVal && newVal.length >= 12) {
          let dd = parseInt(newVal.substr(6, 2));
          if (dd >= 40) {
            this.form.jenis_kelamin = "P";
            dd -= 40;
          } else {
            this.form.jenis_kelamin = "L";
          }
          let mm = parseInt(newVal.substr(8, 2));
          let yy = parseInt(newVal.substr(10, 2));
          if (yy <= 30) {
            let str =
              "" +
              (2000 + yy) +
              "-" +
              ("" + mm).padStart(2, "0") +
              "-" +
              ("" + dd).padStart(2, "0");
            this.form.tanggal_lahir = str;
            this.nik_tgl = str;
          } else {
            let str =
              "" +
              (1900 + yy) +
              "-" +
              ("" + mm).padStart(2, "0") +
              "-" +
              ("" + dd).padStart(2, "0");
            this.form.tanggal_lahir = str;
            this.nik_tgl = str;
          }
          this.$nextTick(() => {
            this.$refs.tgl_lahir.init();
          });
        }
      },
      "form.tanggal_lahir": function (newVal, oldVal) {
        if (this.form.tanggal_lahir == null) {
          this.form.usia_tahun = null;
          this.form.usia_bulan = null;
        } else {
          var birthday = new Date(this.form.tanggal_lahir);
          this.form.usia_tahun = this.form.usia_tahun || moment().diff(birthday, 'years');
          this.form.usia_bulan = this.form.usia_bulan || moment().diff(birthday, 'months') % 12;
        }
      },
      "form.fasyankes_pengirim": function (newVal, oldVal) {
        this.fasyankes = null;
        this.form.fasyankes_id = null
        this.form.nama_rs = null
        this.changeFasyankes(this.form.fasyankes_pengirim)
      },
      "fasyankes": function (newVal, oldVal) {
        this.form.fasyankes_id = null
        if (this.fasyankes) {
          this.form.fasyankes_id = this.fasyankes.id
          this.form.nama_rs = this.fasyankes.nama
        }
      },
      "country": function (newVal, oldVal) {
        this.form.keterangan_warganegara = null
        if (this.country) {
          this.form.keterangan_warganegara = this.country.nama
        }
      },
      "provinsi": function (newVal, oldVal) {
        this.kota = null
        this.kecamatan = null
        this.kelurahan = null
        this.optionKota = [];
        this.optionKecamatan = [];
        this.optionKelurahan = [];
        this.form.provinsi_id = null
        if (this.provinsi) {
          this.form.provinsi_id = this.provinsi.id
          this.form.nama_provinsi = this.provinsi.nama
          this.getKota(this.form.provinsi_id);
        }
      },
      "kota": function (newVal, oldVal) {
        this.kecamatan = null
        this.kelurahan = null
        this.optionKecamatan = [];
        this.optionKelurahan = [];
        this.form.kota_id = null
        if (this.kota) {
          this.form.kota_id = this.kota.id
          this.form.nama_kota = this.kota.nama
          this.getKecamatan(this.form.kota_id);
        }
      },
      "kecamatan": function (newVal, oldVal) {
        this.kelurahan = null
        this.optionKelurahan = [];
        this.form.kecamatan_id = null
        if (this.kecamatan) {
          this.form.kecamatan_id = this.kecamatan.id
          this.form.nama_kecamatan = this.kecamatan.nama
          this.getKelurahan(this.form.kecamatan_id);
        }
      },
      "kelurahan": function (newVal, oldVal) {
        this.form.kelurahan_id = null
        if (this.kelurahan) {
          this.form.kelurahan_id = this.kelurahan.id
          this.form.nama_kelurahan = this.kelurahan.nama
        }
      },
      "pencarian": function (newVal, oldVal) {
        if (this.pencarian) {
          this.getPasien(this.pencarian);
        }
      },
    },
    computed: {
      ...mapGetters({
        jenis_sampel: "options/jenis_sampel"
      }),
      registerId() {
        return this.$route.params.register_id;
      },
      pasienId() {
        return this.$route.params.pasien_id;
      }
    }
  };
</script>