<template>
    <div class="wrapper wrapper-content">
        <portal to="title-name">
            Registrasi Sampel
        </portal>
        <portal to="title-action">
            <div class="title-action">
                <nuxt-link to="/registrasi/sampel" class="btn btn-primary">Kembali</nuxt-link>
            </div>
        </portal>

        <div class="row">
            <div class="col-lg-12">
                <Ibox title="Registrasi Sampel">
                    <form @submit.prevent="submit" @keydown="form.onKeydown($event)">

                        <h4 class="mb-1 mt-0">
                            Identitas Pengirim
                        </h4>
                        <p>Lengkapi Form dengan Identitas Pengirim</p>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">Instansi Pengirim <span style="color:red">*</span>
                            </label>
                            <div class="col-md-8 col-lg-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="reg_fasyankes_pengirim"
                                        value="rumah_sakit" v-model="form.reg_fasyankes_pengirim" required>
                                    <label class="form-check-label" for="fasyanrs">Rumah Sakit</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="reg_fasyankes_pengirim"
                                        value="dinkes" v-model="form.reg_fasyankes_pengirim" required>
                                    <label class="form-check-label" for="fasyandinkes">Dinkes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="reg_fasyankes_pengirim"
                                        value="puskesmas" v-model="form.reg_fasyankes_pengirim" required>
                                    <label class="form-check-label" for="fasyandinkes">Puskesmas</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">Nama Rumah Sakit/Dinkes <span style="color:red">*</span>
                            </label>
                            <div class="col-md-8 col-lg-6">
                                <multiselect v-model="fasyankes" :options="optionFasyankes" track-by="nama" label="nama"
                                    placeholder="Nama Rumah Sakit/Dinkes"
                                    :class="{ 'is-invalid': form.errors.has('reg_fasyankes_id') }">
                                </multiselect>
                                <has-error :form="form" field="reg_fasyankes_id" />
                            </div>
                        </div>

                        <hr>
                        <h4 class="mb-1 mt-0">
                            Identitas Pasien
                        </h4>
                        <p>Lengkapi Form dengan Identitas Pasien</p>
                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Pencarian
                            </label>
                            <div class="col-md-8 col-lg-6">
                                <multiselect v-model="pencarian" :options="optionPencarian" track-by="name" label="name"
                                    placeholder="Pilih NIK/Nama/No Telp" @search-change="asyncFind" :loading="isLoading"
                                    :searchable="true" :internal-search="false" :clear-on-select="false"
                                    :show-no-results="false" :hide-selected="true">
                                </multiselect>
                            </div>
                        </div>
                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Nama Pasien
                                <span style="color:red">*</span>
                            </label>
                            <div class="col-md-8 col-lg-6">
                                <input class="form-control" type="text" name="reg_nama_pasien" placeholder=""
                                    :class="{ 'is-invalid': form.errors.has('reg_nama_pasien') }"
                                    v-model="form.reg_nama_pasien" />
                                <has-error :form="form" field="reg_nama_pasien" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                NIK
                            </label>
                            <div class="col-md-8 col-lg-6">
                                <input class="form-control" type="text" name="reg_nik" placeholder=""
                                    :class="{ 'is-invalid': form.errors.has('reg_nik') }" v-model="form.reg_nik"
                                    maxlength="16" />
                                <has-error :form="form" field="reg_nik" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Tempat Lahir
                            </label>
                            <div class="col-md-8 col-lg-6"
                                :class="{ 'is-invalid': form.errors.has('reg_tempatlahir') }">
                                <input class="form-control" type="text" name="reg_tempatlahir" placeholder=""
                                    v-model="form.reg_tempatlahir" />
                                <has-error :form="form" field="reg_tempatlahir" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Tanggal Lahir
                            </label>
                            <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_tgllahir') }">
                                <dropdown-datepicker v-model="form.reg_tgllahir" :minYear="1900" :daySuffixes="false"
                                    :maxYear="(new Date).getFullYear()" displayFormat="dmy" sortYear="asc"
                                    :default-date="form.reg_tgllahir" ref="tgl_lahir"
                                    :wrapper-class="form.errors.has('reg_tgllahir') ? 'is-invalid' : ''">
                                </dropdown-datepicker>
                                <has-error :form="form" field="reg_tgllahir" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Usia
                            </label>
                            <div class="col-md-3 col-lg-2" :class="{ 'is-invalid': form.errors.has('reg_usia_tahun') }">
                                <input class="form-control" type="number" name="reg_usia_tahun" placeholder="Tahun"
                                    v-model="form.reg_usia_tahun" />
                                <has-error :form="form" field="reg_usia_tahun" />
                            </div>
                            <div class="col-md-3 col-lg-2" :class="{ 'is-invalid': form.errors.has('reg_usia_bulan') }">
                                <input class="form-control" type="number" name="reg_usia_bulan" placeholder="Bulan"
                                    v-model="form.reg_usia_bulan" />
                                <has-error :form="form" field="reg_usia_bulan" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Jenis Kelamin

                            </label>
                            <div class="col-md-8 col-lg-6">
                                <div class="form-check form-check-inline"
                                    :class="{ 'is-invalid': form.errors.has('reg_jk') }">
                                    <label class="fancy-radio custom-color-green m-0 w-100">
                                        <input v-model="form.reg_jk" value="L" type="radio">
                                        <span><i></i>Laki-laki</span>
                                    </label>
                                    <!-- <label class="form-check-label">
                                        <input class="form-check-input" type="radio" v-model="form.reg_jk"
                                            value="L" />
                                        Laki-laki</label> -->
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="fancy-radio custom-color-green m-0 w-100">
                                        <input v-model="form.reg_jk" value="P" type="radio">
                                        <span><i></i>Perempuan</span>
                                    </label>
                                    <!-- <label class="form-check-label">
                                        <input class="form-check-input" type="radio" v-model="form.reg_jk"
                                            value="P" />
                                        Perempuan</label> -->
                                </div>
                                <has-error :form="form" field="reg_jk" />
                            </div>
                        </div>



                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                No. Telp / HP

                            </label>
                            <div class="col-md-8 col-lg-6">
                                <input class="form-control" type="text" name="reg_nohp" placeholder=""
                                    v-model="form.reg_nohp" :class="{ 'is-invalid': form.errors.has('reg_nohp') }" />
                                <has-error :form="form" field="reg_nohp" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Alamat

                            </label>
                            <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_alamat') }">
                                <textarea class="multisteps-form__input form-control" type="text" name="reg_alamat"
                                    v-model="form.reg_alamat"></textarea>
                                <has-error :form="form" field="reg_alamat" />
                            </div>
                        </div>

                        <div class="row mt-2">
                            <label class="col-md-3 col-lg-2">
                                RT / RW
                            </label>
                            <div class="input-group col-md-3 col-lg-2"
                                :class="{ 'is-invalid':form.errors.has('reg_rt') }">
                                <span class="input-group-addon bootstrap-touchspin-postfix input-group-append">
                                    <span class="input-group-text">RT </span>
                                </span>
                                <input class="form-control" type="number" name="reg_rt" v-model="form.reg_rt" min="1"
                                    step="1" max="999">
                            </div>
                            <div class="input-group col-md-3 col-lg-2"
                                :class="{ 'is-invalid':form.errors.has('reg_rw') }">
                                <div class="input-group-addon bootstrap-touchspin-postfix input-group-append">
                                    <span class="input-group-text">RW </span>
                                </div>
                                <input class="form-control" type="number" name="reg_domisilirw" min="1" step="1"
                                    max="999" v-model="form.reg_rw" />
                            </div>
                            <has-error :form="form" field="reg_rt" />
                            <has-error :form="form" field="reg_rw" />
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Provinsi
                            </label>
                            <div class="col-md-8 col-lg-6">
                                <multiselect v-model="provinsi" :options="optionProvinsi" track-by="nama" label="nama"
                                    placeholder="Pilih Provinsi"
                                    :class="{ 'is-invalid': form.errors.has('reg_provinsi') }">
                                </multiselect>
                                <has-error :form="form" field="reg_provinsi" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Kota / Kabupaten
                            </label>
                            <div class="col-md-8 col-lg-6">
                                <multiselect v-model="kota" :options="optionKota" track-by="nama" label="nama"
                                    placeholder="Pilih Kota / Kabupaten"
                                    :class="{ 'is-invalid': form.errors.has('reg_kota') }">
                                </multiselect>
                                <has-error :form="form" field="reg_kota" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Kecamatan
                            </label>
                            <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_kecamatan') }">
                                <multiselect v-model="kecamatan" :options="optionKecamatan" track-by="nama" label="nama"
                                    placeholder="Pilih Kecamatan"
                                    :class="{ 'is-invalid': form.errors.has('reg_kecamatan') }">
                                </multiselect>
                                <has-error :form="form" field="reg_kecamatan" />
                            </div>
                        </div>


                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Kelurahan / Desa
                            </label>
                            <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_kelurahan') }">
                                <multiselect v-model="kelurahan" :options="optionKelurahan" track-by="nama" label="nama"
                                    placeholder="Pilih Kelurahan / Desa"
                                    :class="{ 'is-invalid': form.errors.has('reg_kelurahan') }">
                                </multiselect>
                                <has-error :form="form" field="reg_kelurahan" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">Kewarganegaraan</label>
                            <div class="col-md-8 col-lg-6">
                                <select v-model="form.reg_kewarganegaraan"
                                    :class="{ 'is-invalid':form.errors.has('reg_kewarganegaraan') }"
                                    class="multisteps-form__input form-control col-md-8 col-lg-6"
                                    name="reg_kewarganegaraan">
                                    <option value="WNI">WNI</option>
                                    <option value="WNA">WNA</option>
                                </select>
                                <has-error :form="form" field="reg_kewarganegaraan" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">Status Pasien</label>
                            <div class="col-md-8 col-lg-6">
                                <select v-model="form.reg_status"
                                    :class="{ 'is-invalid':form.errors.has('reg_status') }"
                                    class="multisteps-form__input form-control col-md-8 col-lg-6" name="reg_status">
                                    <option value="otg">OTG</option>
                                    <option value="odp">ODP</option>
                                    <option value="pdp">PDP</option>
                                    <option value="positif">Positif</option>
                                    <option value="tanpa status">Tanpa Status</option>
                                </select>
                                <has-error :form="form" field="reg_status" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Kategori
                            </label>
                            <div class="col-md-8 col-lg-6">
                                <input class="multisteps-form__input form-control" type="text" name="reg_sumber_pasien"
                                    v-model="form.reg_sumber_pasien"
                                    :class="{ 'is-invalid': form.errors.has('reg_sumber_pasien') }" />
                                <has-error :form="form" field="reg_sumber_pasien" />
                            </div>
                        </div>

                        <hr>
                        <h4 class="mb-1 mt-0">
                            Identitas Sampel
                        </h4>
                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Swab Ke
                            </label>
                            <div class="col-md-8 col-lg-6">
                                <input class="multisteps-form__input form-control" type="number" name="reg_swab_ke"
                                    v-model="form.reg_swab_ke"
                                    :class="{ 'is-invalid': form.errors.has('reg_swab_ke') }" />
                                <has-error :form="form" field="reg_swab_ke" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Tanggal Swab
                            </label>
                            <div class="col-md-8 col-lg-6">
                                <date-picker format="d MMMM yyyy" input-class="multisteps-form__input form-control"
                                    :monday-first="true" v-model="form.reg_tanggal_swab"
                                    :class="{ 'is-invalid': form.errors.has('reg_tanggal_swab') }" />
                                <has-error :form="form" field="reg_tanggal_swab" />
                            </div>
                        </div>
                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Jenis Sampel <span style="color:red">*</span>
                            </label>
                            <div class="col-md-8 col-lg-6">
                                <select class="form-control" v-model="form.reg_sampel_jenis_sampel"
                                    :class="{ 'is-invalid': form.errors.has(`reg_sampel_jenis_sampel`) }">
                                    <option :value="item.id" v-for="(item, $key) in jenis_sampel" :key="$key">
                                        {{ item.text }}</option>
                                </select>
                                <has-error :form="form" :field="`reg_sampel_jenis_sampel`" />
                                <div v-if="form.reg_sampel_jenis_sampel == 999999">
                                    <small for="specify">Jenis Lainnya (isi apabila tidak tercantum
                                        diatas)</small>
                                    <input type="text" class="form-control" v-model="form.reg_sampel_namadiluarjenis"
                                        placeholder="isi apabila tidak tercantum"
                                        :class="{ 'is-invalid': form.errors.has(`reg_sampel_namadiluarjenis`) }"
                                        preventForm />
                                    <has-error :form="form" :field="`reg_sampel_namadiluarjenis`" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Nomor sampel <span style="color:red">*</span>
                            </label>
                            <div class="col-md-8 col-lg-6">
                                <input class="multisteps-form__input form-control" type="text" name="reg_sampel_nomor"
                                    v-model="form.reg_sampel_nomor"
                                    :class="{ 'is-invalid': form.errors.has('reg_sampel_nomor') }" />
                                <has-error :form="form" field="reg_sampel_nomor" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Keterangan Lainnya
                            </label>
                            <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_keterangan') }">
                                <textarea class="form-control" type="text" name="reg_keterangan"
                                    v-model="form.reg_keterangan" rows="6"></textarea>
                                <has-error :form="form" field="reg_keterangan" />
                            </div>
                        </div>

                        <div class="form-group row mt-4 text-center justify-content-center">
                            <v-button :loading="form.busy" class="btn btn-md btn-primary block  m-b">
                                <i class="fa fa-save"></i> Simpan Data Register
                            </v-button>
                        </div>
                    </form>
                </Ibox>
            </div>
        </div>
    </div>
</template>

<script>
    import Form from "vform";
    import axios from 'axios';
    import {
        mapGetters
    } from "vuex";
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
                    reg_kewarganegaraan: 'WNI',
                    reg_nama_pasien: null,
                    reg_nik: null,
                    reg_tempatlahir: null,
                    reg_tgllahir: null,
                    reg_nohp: null,
                    reg_kota: null,
                    reg_kecamatan: null,
                    reg_kelurahan: null,
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
                    reg_sumber_pasien: null
                }),
                optionFasyankes: [],
                optionProvinsi: [],
                optionKecamatan: [],
                optionKelurahan: [],
                optionKota: [],
                optionPencarian: [],
                fasyankes: {},
                provinsi: {},
                kota: {},
                kecamatan: {},
                kelurahan: {},
                wilayah: true,
                pencarian: null,
                pelaporan: null,
                isLoading: false
            };
        },
        methods: {
            async asyncFind(query) {
                this.isLoading = true
                if (query != '') {
                    let resp = await axios.get('/v1/pelaporan/fetch?keyword=' + query);
                    this.optionPencarian = resp.data.data.content
                    this.isLoading = false
                }
            },
            async changeFasyankes(tipe) {
                let resp = await axios.get('/v1/list-fasyankes-jabar?tipe=' + tipe)
                this.optionFasyankes = resp.data;
            },
            initForm() {
                this.form = new Form({
                    reg_fasyankes_id: null,
                    reg_kewarganegaraan: 'WNI',
                    reg_nama_pasien: null,
                    reg_nik: null,
                    reg_tempatlahir: null,
                    reg_tgllahir: null,
                    reg_nohp: null,
                    reg_kota: null,
                    reg_kecamatan: null,
                    reg_kelurahan: null,
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
                    this.form.reg_status = pelaporan.status.toLowerCase();
                    this.form.reg_alamat = pelaporan.address_detail;
                    if (this.form.reg_tgllahir) {
                        this.$refs.tgl_lahir.init();
                    }
                    console.log(pelaporan);
                }
            },
            async getProvinsi() {
                let resp = await axios.get('/v1/list-provinsi/');
                this.optionProvinsi = resp.data;
            },
            async getKota(provinsi) {
                let resp = await axios.get('/v1/list-kota/' + provinsi);
                this.optionKota = resp.data;
            },
            async getKecamatan(kabupaten) {
                let resp = await axios.get('/v1/list-kecamatan/' + kabupaten);
                this.optionKecamatan = resp.data;
            },
            async getKelurahan(kecamatan) {
                let resp = await axios.get('/v1/list-kelurahan/' + kecamatan);
                this.optionKelurahan = resp.data;
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
                    // console.log('Response : ', response);
                    this.initForm();
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
                    var birthday = new Date(this.form.reg_tgllahir)
                    var now = new Date();
                    var yearNow = now.getYear();
                    var monthNow = now.getMonth() + 1;
                    var dayNow = now.getDate();

                    var yearDob = birthday.getYear();
                    var monthDob = birthday.getMonth() + 1;
                    var dayDob = birthday.getDate();
                    var second = 1000;
                    var minute = second * 60;
                    var hour = minute * 60;
                    var day = hour * 24;
                    var month = day * 30;
                    var year = day * 365;
                    var ms = now - birthday;
                    var msb = Math.round(ms % year)
                    this.form.reg_usia_tahun = Math.round(ms / year)
                    this.form.reg_usia_bulan = Math.round(msb / month)
                }

            },
            "form.reg_fasyankes_pengirim": function (newVal, oldVal) {
                this.fasyankes = {};
                this.changeFasyankes(this.form.reg_fasyankes_pengirim)
            },
            "fasyankes": function (newVal, oldVal) {
                this.form.reg_fasyankes_id = null
                if (this.fasyankes) {
                    this.form.reg_fasyankes_id = this.fasyankes.id
                }
            },
            "provinsi": function (newVal, oldVal) {
                console.log(oldVal);
                this.kota = {}
                this.kecamatan = {}
                this.kelurahan = {}
                this.optionKota = [];
                this.optionKecamatan = [];
                this.optionKelurahan = [];
                this.form.reg_provinsi = null
                if (this.provinsi) {
                    this.form.reg_provinsi = this.provinsi.id
                    this.getKota(this.form.reg_provinsi);
                }
            },
            "kota": function (newVal, oldVal) {
                this.kecamatan = {}
                this.kelurahan = {}
                this.optionKecamatan = [];
                this.optionKelurahan = [];
                this.form.reg_kota = null
                if (this.kota) {
                    this.form.reg_kota = this.kota.id
                    this.getKecamatan(this.form.reg_kota);
                }
            },
            "kecamatan": function (newVal, oldVal) {
                this.kelurahan = {}
                this.optionKelurahan = [];
                this.form.reg_kecamatan = null
                if (this.kecamatan) {
                    this.form.reg_kecamatan = this.kecamatan.id
                    this.getKelurahan(this.form.reg_kecamatan);
                }
            },
            "kelurahan": function (newVal, oldVal) {
                this.form.reg_kelurahan = null
                if (this.kelurahan) {
                    this.form.reg_kelurahan = this.kelurahan.id
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