<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">
      Registrasi Sampel
    </portal>
    <portal to="title-action">
      <div class="title-action">
        <nuxt-link to="/registrasi/sampel" class="btn btn-black">
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
              <div class="col-md-8" :class="{ 'is-invalid': form.errors.has('reg_fasyankes_pengirim') }">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="reg_fasyankes_pengirim" value="rumah_sakit"
                    v-model="form.reg_fasyankes_pengirim" required>
                  <label class="form-check-label" for="fasyanrs">Rumah Sakit</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="reg_fasyankes_pengirim" value="dinkes"
                    v-model="form.reg_fasyankes_pengirim" required>
                  <label class="form-check-label" for="fasyandinkes">Dinkes</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="reg_fasyankes_pengirim" value="puskesmas"
                    v-model="form.reg_fasyankes_pengirim" required>
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
                  placeholder="Nama Rumah Sakit/Dinkes" :class="{ 'is-invalid': form.errors.has('reg_fasyankes_id') }">
                </multiselect>
                <has-error :form="form" field="reg_fasyankes_id" />
              </div>
            </div>
          </Ibox>

          <Ibox title="Identitas Pasien">
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Pencarian
              </div>
              <div class="col-md-8">
                <multiselect v-model="pencarian" :options="optionPencarian" track-by="name" label="name"
                  placeholder="Cari berdasarkan NIK/Nama/No Telp" @search-change="asyncFind"
                  :loading="isLoadingPencarian" :searchable="true" :internal-search="false" :clear-on-select="false"
                  :show-no-results="false" :hide-selected="true">
                </multiselect>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Nama Pasien
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <input class="form-control" type="text" name="reg_nama_pasien" placeholder="Nama Pasien" required
                  v-model="form.reg_nama_pasien" :class="{ 'is-invalid': form.errors.has('reg_nama_pasien') }" />
                <has-error :form="form" field="reg_nama_pasien" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                NIK
              </div>
              <div class="col-md-8">
                <input class="form-control" type="text" name="reg_nik" placeholder="NIK" v-model="form.reg_nik"
                  maxlength="16" :class="{ 'is-invalid': form.errors.has('reg_nik') }" />
                <has-error :form="form" field="reg_nik" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Tempat Lahir
              </div>
              <div class="col-md-8">
                <input class="form-control" type="text" name="reg_tempatlahir" placeholder="Tempat Lahir"
                  v-model="form.reg_tempatlahir" :class="{ 'is-invalid': form.errors.has('reg_tempatlahir') }" />
                <has-error :form="form" field="reg_tempatlahir" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Tanggal Lahir
              </div>
              <div class="col-md-8">
                <date-picker placeholder="Tanggal Lahir" format="dd MMMM yyyy" ref="tgl_lahir"
                  input-class="form-control" :monday-first="true" v-model="form.reg_tgllahir"
                  :class="{ 'is-invalid': form.errors.has('reg_tgllahir') }" />
                <has-error :form="form" field="reg_tgllahir" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Usia
              </div>
              <div class="col-md-3 col-lg-2">
                <input class="form-control" type="number" name="reg_usia_tahun" placeholder="Tahun"
                  v-model="form.reg_usia_tahun" :class="{ 'is-invalid': form.errors.has('reg_usia_tahun') }" />
                <has-error :form="form" field="reg_usia_tahun" />
              </div>
              <div class="col-md-3 col-lg-2">
                <input class="form-control" type="number" name="reg_usia_bulan" placeholder="Bulan"
                  v-model="form.reg_usia_bulan" :class="{ 'is-invalid': form.errors.has('reg_usia_bulan') }" />
                <has-error :form="form" field="reg_usia_bulan" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Jenis Kelamin
              </div>
              <div class="col-md-8">
                <div class="form-check form-check-inline" :class="{ 'is-invalid': form.errors.has('reg_jk') }">
                  <label class="fancy-radio custom-color-green m-0 w-100">
                    <input v-model="form.reg_jk" value="L" type="radio">
                    <span><i />Laki-laki</span>
                  </label>
                </div>
                <div class="form-check form-check-inline">
                  <label class="fancy-radio custom-color-green m-0 w-100">
                    <input v-model="form.reg_jk" value="P" type="radio">
                    <span><i />Perempuan</span>
                  </label>
                </div>
                <has-error :form="form" field="reg_jk" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                No. Telp / HP
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <input class="form-control" type="text" name="reg_nohp" placeholder="Nomor Telepon / HP" v-model="form.reg_nohp"
                  :class="{ 'is-invalid': form.errors.has('reg_nohp') }" required />
                <has-error :form="form" field="reg_nohp" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Alamat
              </div>
              <div class="col-md-8">
                <textarea class="multisteps-form__input form-control" type="text" name="reg_alamat" placeholder="Alamat"
                  v-model="form.reg_alamat" :class="{ 'is-invalid': form.errors.has('reg_alamat') }"></textarea>
                <has-error :form="form" field="reg_alamat" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                RT / RW
              </div>
              <div class="input-group col-md-3 col-lg-2 flex-text-center">
                <input class="form-control" placeholder="RT" readonly="readonly" />
                <input class="form-control" type="text" name="reg_rt" v-model="form.reg_rt"
                  :class="{ 'is-invalid':form.errors.has('reg_rt') }">
              </div>
              <div class="input-group col-md-3 col-lg-2 flex-text-center">
                <input class="form-control" placeholder="RW" readonly="readonly" />
                <input class="form-control" type="text" name="reg_domisilirw" v-model="form.reg_rw"
                  :class="{ 'is-invalid':form.errors.has('reg_rw') }" />
              </div>
              <has-error :form="form" field="reg_rt" />
              <has-error :form="form" field="reg_rw" />
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Provinsi
              </div>
              <div class="col-md-8">
                <multiselect v-model="provinsi" :options="optionProvinsi" track-by="nama" label="nama"
                  placeholder="Pilih Provinsi" :class="{ 'is-invalid': form.errors.has('reg_provinsi') }">
                </multiselect>
                <has-error :form="form" field="reg_provinsi" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Kota / Kabupaten
              </div>
              <div class="col-md-8">
                <multiselect v-model="kota" :options="optionKota" track-by="nama" label="nama"
                  placeholder="Pilih Kota / Kabupaten" :loading="isLoadingKota" :searchable="true"
                  :class="{ 'is-invalid': form.errors.has('reg_kode_kota') }">
                </multiselect>
                <has-error :form="form" field="reg_kode_kota" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Kecamatan
              </div>
              <div class="col-md-8">
                <multiselect v-model="kecamatan" :options="optionKecamatan" track-by="nama" label="nama"
                  placeholder="Pilih Kecamatan" :loading="isLoadingKecamatan" :searchable="true"
                  :class="{ 'is-invalid': form.errors.has('reg_kode_kecamatan') }">
                </multiselect>
                <has-error :form="form" field="reg_kode_kecamatan" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Kelurahan / Desa
              </div>
              <div class="col-md-8">
                <multiselect v-model="kelurahan" :options="optionKelurahan" track-by="nama" label="nama"
                  placeholder="Pilih Kelurahan / Desa" :loading="isLoadingKelurahan" :searchable="true"
                  :class="{ 'is-invalid': form.errors.has('reg_kode_kelurahan') }">
                </multiselect>
                <has-error :form="form" field="reg_kode_kelurahan" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Kewarganegaraan
              </div>
              <div class="col-md-8">
                <select v-model="form.reg_kewarganegaraan" class="multisteps-form__input form-control col-md-8 col-lg-6"
                  name="reg_kewarganegaraan" :class="{ 'is-invalid': form.errors.has('reg_kewarganegaraan') }">
                  <option value="WNI">WNI</option>
                  <option value="WNA">WNA</option>
                </select>
                <has-error :form="form" field="reg_kewarganegaraan" />
                <multiselect v-if="form.reg_kewarganegaraan === 'WNA'" v-model="country" :options="optionCountry" track-by="nama" label="nama"
                  placeholder="Pilih Negara" class="mt-3" :class="{ 'is-invalid': form.errors.has('reg_keterangan_warganegara') }">
                </multiselect>
                <has-error :form="form" field="reg_keterangan_warganegara" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Kriteria Pasien
              </div>
              <div class="col-md-8">
                <select v-model="form.reg_status" class="multisteps-form__input form-control col-md-8 col-lg-6"
                  name="reg_status" :class="{ 'is-invalid': form.errors.has('reg_status') }">
                  <option v-for="index in pasien_status_option" v-bind:key="index.value" :value="index.value">
                    {{index.text}}
                  </option>
                </select>
                <has-error :form="form" field="reg_status" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Kategori
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <input class="multisteps-form__input form-control" type="text" name="reg_sumber_pasien" required placeholder="Kategori"
                  v-model="form.reg_sumber_pasien" :class="{ 'is-invalid': form.errors.has('reg_sumber_pasien') }" />
                <has-error :form="form" field="reg_sumber_pasien" />
              </div>
            </div>
          </Ibox>

          <Ibox title="Identitas Sampel">
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Swab Ke
              </div>
              <div class="col-md-8">
                <input class="multisteps-form__input form-control" type="number" name="reg_swab_ke"
                  v-model="form.reg_swab_ke" :class="{ 'is-invalid': form.errors.has('reg_swab_ke') }" />
                <has-error :form="form" field="reg_swab_ke" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Tanggal Swab
              </div>
              <div class="col-md-8">
                <date-picker format="d MMMM yyyy" input-class="multisteps-form__input form-control" :monday-first="true"
                  v-model="form.reg_tanggal_swab" :class="{ 'is-invalid': form.errors.has('reg_tanggal_swab') }" />
                <has-error :form="form" field="reg_tanggal_swab" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Jenis Sampel
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <select class="form-control" v-model="form.reg_sampel_jenis_sampel" required
                  :class="{ 'is-invalid': form.errors.has(`reg_sampel_jenis_sampel`) }">
                  <option :value="item.id" v-for="(item, $key) in jenis_sampel" :key="$key">
                    {{ item.text }}</option>
                </select>
                <has-error :form="form" :field="`reg_sampel_jenis_sampel`" />
                <div v-if="form.reg_sampel_jenis_sampel == 999999">
                  <small for="specify">
                    Jenis Lainnya (isi apabila tidak tercantum diatas)
                  </small>
                  <input type="text" class="form-control" v-model="form.reg_sampel_namadiluarjenis"
                    placeholder="isi apabila tidak tercantum"
                    :class="{ 'is-invalid': form.errors.has(`reg_sampel_namadiluarjenis`) }" preventForm />
                  <has-error :form="form" :field="`reg_sampel_namadiluarjenis`" />
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Nomor sampel
                <span style="color:red">*</span>
              </div>
              <div class="col-md-8">
                <input class="multisteps-form__input form-control" type="text" name="reg_sampel_nomor" required
                  v-model="form.reg_sampel_nomor" :class="{ 'is-invalid': form.errors.has('reg_sampel_nomor') }" />
                <has-error :form="form" field="reg_sampel_nomor" />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-4 flex-text-center">
                Keterangan Lainnya
              </div>
              <div class="col-md-8">
                <textarea class="form-control" type="text" name="reg_keterangan" v-model="form.reg_keterangan" rows="6"
                  :class="{ 'is-invalid': form.errors.has('reg_keterangan') }" />
                <has-error :form="form" field="reg_keterangan" />
              </div>
            </div>
          </Ibox>
          
          <div class="col-md-12 mb-4">
            <button :loading="form.busy" class="btn btn-md btn-primary">
              <i class="fa fa-check" /> Simpan Data Register
            </button>
            <nuxt-link to="/registrasi/sampel" class="btn btn-clear">
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
  import moment from 'moment';
  import {
    mapGetters
  } from "vuex";
  import {
    pasienStatus
  } from '~/assets/js/constant/enum';
  export default {
    middleware: "auth",
    computed: mapGetters({
      jenis_sampel: "options/jenis_sampel",
    }),
    async asyncData({
      store
    }) {
      if (!store.getters['options/jenis_sampel'].length) {
        await store.dispatch('options/fetchJenisSampel')
      }
      return {}
    },
    data() {
      return {
        form: new Form({
          reg_fasyankes_id: null,
          reg_fasyankes_pengirim: null,
          reg_nama_rs: null,
          reg_kewarganegaraan: 'WNI',
          reg_keterangan_warganegara: null,
          reg_nama_pasien: null,
          reg_nik: null,
          reg_tempatlahir: null,
          reg_tgllahir: null,
          reg_nohp: null,
          reg_alamat: null,
          reg_rt: null,
          reg_rw: null,
          reg_jk: null,
          reg_usia_tahun: null,
          reg_usia_bulan: null,
          reg_sampel_namadiluarjenis: null,
          reg_sampel_jenis_sampel: null,
          reg_sampel_nomor: null,
          reg_status: null,
          reg_swab_ke: null,
          reg_tanggal_swab: null,
          reg_sumber_pasien: null,
          reg_pelaporan_id: null,
          reg_pelaporan_id_case: null,
          reg_kode_provinsi: null,
          reg_nama_provinsi: null,
          reg_kode_kota: null,
          reg_nama_kota: null,
          reg_kode_kecamatan: null,
          reg_nama_kecamatan: null,
          reg_kode_kelurahan: null,
          reg_nama_kelurahan: null,
          reg_keterangan: null
        }),
        optionFasyankes: [],
        optionCountry: [],
        optionProvinsi: [],
        optionKecamatan: [],
        optionKelurahan: [],
        optionKota: [],
        optionPencarian: [],
        fasyankes: null,
        country: null,
        provinsi: null,
        kota: null,
        kecamatan: null,
        kelurahan: null,
        pencarian: null,
        pelaporan: null,
        isLoadingPencarian: false,
        isLoadingKota: false,
        isLoadingKecamatan: false,
        isLoadingKelurahan: false,
        pasien_status_option: pasienStatus,
      };
    },
    methods: {
      async asyncFind(query) {
        this.isLoadingPencarian = true
        this.optionPencarian = []
        if (query != '') {
          let resp = await this.$axios.get('/v1/pelaporan/fetch?search=' + query);
          this.optionPencarian = resp.data.data.content
        }
        this.isLoadingPencarian = false
      },
      async changeFasyankes(tipe) {
        let resp = await this.$axios.get('/v1/list-fasyankes-jabar?tipe=' + tipe)
        this.optionFasyankes = resp.data;
      },
      initForm() {
        this.form = new Form({
          reg_fasyankes_id: null,
          reg_fasyankes_pengirim: null,
          reg_nama_rs: null,
          reg_kewarganegaraan: 'WNI',
          reg_keterangan_warganegara: null,
          reg_nama_pasien: null,
          reg_nik: null,
          reg_tempatlahir: null,
          reg_tgllahir: null,
          reg_nohp: null,
          reg_alamat: null,
          reg_rt: null,
          reg_rw: null,
          reg_jk: null,
          reg_usia_tahun: null,
          reg_usia_bulan: null,
          reg_sampel_namadiluarjenis: null,
          reg_sampel_jenis_sampel: null,
          reg_sampel_nomor: null,
          reg_status: null,
          reg_swab_ke: null,
          reg_tanggal_swab: null,
          reg_sumber_pasien: null,
          reg_pelaporan_id: null,
          reg_pelaporan_id_case: null,
          reg_kode_provinsi: null,
          reg_nama_provinsi: null,
          reg_kode_kota: null,
          reg_nama_kota: null,
          reg_kode_kecamatan: null,
          reg_nama_kecamatan: null,
          reg_kode_kelurahan: null,
          reg_nama_kelurahan: null,
          reg_keterangan: null
        })
        this.pencarian = null
      },
      async getPasien(pelaporan) {
        if (pelaporan) {
          this.form.reg_nama_pasien = pelaporan.name;
          this.form.reg_usia_tahun = pelaporan.age;
          this.form.reg_nik = pelaporan.nik;
          this.form.reg_nohp = pelaporan.phone_number;
          this.form.reg_kewarganegaraan = pelaporan.nationality;
          this.form.reg_jk = pelaporan.gender;
          this.form.reg_tgllahir = pelaporan.birth_date;
          this.form.reg_status = pelaporan.status_code;
          this.form.reg_alamat = pelaporan.address_detail;
          this.form.reg_pelaporan_id = pelaporan.id;
          this.form.reg_pelaporan_id_case = pelaporan.id_case;
          this.form.reg_kode_provinsi = pelaporan.address_province_code;
          this.form.reg_nama_provinsi = pelaporan.address_province_name;
          this.form.reg_kode_kabupaten = pelaporan.address_district_code;
          this.form.reg_nama_kabupaten = pelaporan.address_district_name;
          this.form.reg_kode_kecamatan = pelaporan.address_subdistrict_code;
          this.form.reg_nama_kecamatan = pelaporan.address_subdistrict_name;
          this.form.reg_kode_kelurahan = pelaporan.address_village_code;
          this.form.reg_nama_kelurahan = pelaporan.address_village_name;

          this.provinsi = {
            id: pelaporan.address_province_code,
            nama: pelaporan.address_province_name
          };
          await this.getKota(pelaporan.address_province_code);
          this.kota = {
            id: pelaporan.address_district_code,
            nama: pelaporan.address_district_name
          };
          await this.getKecamatan(pelaporan.address_district_code);
          this.kecamatan = {
            id: pelaporan.address_subdistrict_code,
            nama: pelaporan.address_subdistrict_name
          };
          await this.getKelurahan(pelaporan.address_subdistrict_code);
          this.kelurahan = {
            id: pelaporan.address_village_code,
            nama: pelaporan.address_village_name
          };
          if (this.form.reg_tgllahir) {
            this.$refs.tgl_lahir.init();
          }
        }
      },
      async getCountry() {
        let resp = await this.$axios.get('/v1/list-negara/');
        this.optionCountry = resp.data;
      },
      async getProvinsi() {
        let resp = await this.$axios.get('/v1/list-provinsi/');
        this.optionProvinsi = resp.data;
      },
      async getKota(provinsi) {
        this.isLoadingKota = true;
        if (provinsi) {
          let resp = await this.$axios.get('/v1/list-kota/' + provinsi);
          this.optionKota = resp.data;
          this.isLoadingKota = false;
        } else {
          this.isLoadingKota = false;
        }
      },
      async getKecamatan(kabupaten) {
        this.isLoadingKecamatan = true;
        if (kabupaten) {
          let resp = await this.$axios.get('/v1/list-kecamatan/' + kabupaten);
          this.optionKecamatan = resp.data;
          this.isLoadingKecamatan = false;
        } else {
          this.isLoadingKecamatan = false;
        }
      },
      async getKelurahan(kecamatan) {
        this.isLoadingKelurahan = true;
        if (kecamatan) {
          let resp = await this.$axios.get('/v1/list-kelurahan/' + kecamatan);
          this.optionKelurahan = resp.data;
          this.isLoadingKelurahan = false;
        } else {
          this.isLoadingKelurahan = false;
        }
      },
      async submit() {
        // Submit the form.
        try {
          const response = await this.form.post("/v1/register/sampel");
          this.$toast.success(response.data.message, {
            icon: 'check',
            iconPack: 'fontawesome',
            duration: 5000
          })
          this.initForm();
          this.$router.push("/registrasi/sampel");
        } catch (err) {
          if (err.response && err.response.data.code == 422) {
            this.$nextTick(() => {
              this.form.errors.set(err.response.data.error)
            })
            this.$toast.error('Mohon cek kembali formulir Anda', {
              icon: 'times',
              iconPack: 'fontawesome',
              duration: 5000
            })
          } else {
            this.$swal.fire("Terjadi kesalahan", "Silakan hubungi Admin", "error");
          }
          return;
        }
      }
    },
    head() {
      return {
        title: "Pengambilan / Penerimaan Sampel Baru"
      };
    },
    created() {
      this.getCountry();
      this.getProvinsi();
    },
    watch: {
      "form.reg_nik": function (newVal, oldVal) {
        if (newVal && newVal.length >= 12) {
          let dd = parseInt(newVal.substr(6, 2))
          if (dd >= 40) {
            this.form.reg_jk = 'P'
            dd -= 40
          } else {
            this.form.reg_jk = 'L'
          }
          let mm = parseInt(newVal.substr(8, 2))
          let yy = parseInt(newVal.substr(10, 2))
          if (yy <= 30) {
            let str = '' + (2000 + yy) + '-' + ('' + mm).padStart(2, '0') + '-' + ('' + dd).padStart(2,
              '0')
            this.form.reg_tgllahir = str
          } else {
            let str = '' + (1900 + yy) + '-' + ('' + mm).padStart(2, '0') + '-' + ('' + dd).padStart(2,
              '0')
            this.form.reg_tgllahir = str
          }
          this.$nextTick(() => {
            this.$refs.tgl_lahir.init();
          })
        }
      },
      "form.reg_tgllahir": function (newVal, oldVal) {
        if (this.form.reg_tgllahir == null) {
          this.form.reg_usia_tahun = null;
          this.form.reg_usia_bulan = null;
        } else {
          var birthday = new Date(this.form.reg_tgllahir);
          this.form.reg_usia_tahun = this.form.reg_usia_tahun || moment().diff(birthday, 'years');
          this.form.reg_usia_bulan = this.form.reg_usia_bulan || moment().diff(birthday, 'months') % 12;
        }
      },
      "form.reg_fasyankes_pengirim": function (newVal, oldVal) {
        this.fasyankes = null;
        this.form.reg_fasyankes_id = null
        this.form.reg_nama_rs = null
        this.changeFasyankes(this.form.reg_fasyankes_pengirim)
      },
      "fasyankes": function (newVal, oldVal) {
        this.form.reg_fasyankes_id = null
        if (this.fasyankes) {
          this.form.reg_fasyankes_id = this.fasyankes.id
          this.form.reg_nama_rs = this.fasyankes.nama
        }
      },
      "country": function (newVal, oldVal) {
        this.form.reg_keterangan_warganegara = null
        if (this.country) {
          this.form.reg_keterangan_warganegara = this.country.nama
        }
      },
      "provinsi": function (newVal, oldVal) {
        this.kota = null
        this.kecamatan = null
        this.kelurahan = null
        this.optionKota = [];
        this.optionKecamatan = [];
        this.optionKelurahan = [];
        this.form.reg_kode_provinsi = null
        if (this.provinsi) {
          this.form.reg_kode_provinsi = this.provinsi.id
          this.form.reg_nama_provinsi = this.provinsi.nama
          this.getKota(this.form.reg_kode_provinsi);
        }
      },
      "kota": function (newVal, oldVal) {
        this.kecamatan = null
        this.kelurahan = null
        this.optionKecamatan = [];
        this.optionKelurahan = [];
        this.form.reg_kode_kota = null
        if (this.kota) {
          this.form.reg_kode_kota = this.kota.id
          this.form.reg_nama_kota = this.kota.nama
          this.getKecamatan(this.form.reg_kode_kota);
        }
      },
      "kecamatan": function (newVal, oldVal) {
        this.kelurahan = null
        this.optionKelurahan = [];
        this.form.reg_kode_kecamatan = null
        if (this.kecamatan) {
          this.form.reg_kode_kecamatan = this.kecamatan.id
          this.form.reg_nama_kecamatan = this.kecamatan.nama
          this.getKelurahan(this.form.reg_kode_kecamatan);
        }
      },
      "kelurahan": function (newVal, oldVal) {
        this.form.reg_kode_kelurahan = null
        if (this.kelurahan) {
          this.form.reg_kode_kelurahan = this.kelurahan.id
          this.form.reg_nama_kelurahan = this.kelurahan.nama
        }
      },
      "pencarian": function (newVal, oldVal) {
        if (this.pencarian) {
          this.getPasien(this.pencarian);
        }
      },
    }
  };
</script>