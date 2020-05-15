<template>
    <div class="wrapper wrapper-content">
        <portal to="title-name">
            Update Registrasi Pasien
        </portal> 
        <portal to="title-action">
            <div class="title-action">
                <nuxt-link to="/registrasi/mandiri" class="btn btn-primary">Kembali</nuxt-link>
            </div>
        </portal>
        <div class="row">
            <div class="col-lg-12">
                <Ibox title="Penerimaan atau Pengambilan Sampel">
                    <form @submit.prevent="submit" @keydown="form.onKeydown($event)">
                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Nomor Registrasi
                                <span style="color:red">*</span>
                            </label>
                            <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_no') }">
                                <input class="form-control" type="text" name="reg_no" placeholder="Nomor Registrasi"
                                    required v-model="form.reg_no" disabled />
                                <has-error :form="form" field="reg_no" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">Kewarganegaraan <span style="color:red;">*</span></label>
                            <div class="col-md-8 col-lg-6">
                                <select v-model="form.reg_kewarganegaraan"
                                    :class="{ 'is-invalid':form.errors.has('reg_kewarganegaraan') }"
                                    class="multisteps-form__input form-control col-md-8 col-lg-6" name="reg_kewarganegaraan">
                                    <option value="WNI">WNI</option>
                                    <option value="WNA">WNA</option>
                                </select>
                                <has-error :form="form" field="reg_kewarganegaraan" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Kategori
                                <span style="color:red">*</span>
                            </label>
                            <div class="col-md-8 col-lg-6">
                                <div class="form-check form-check-inline"
                                    :class="{ 'is-invalid': form.errors.has('reg_sumberpasien') }">
                                    <label class="fancy-radio custom-color-green m-0 w-100">
                                        <input type="radio" v-model="form.reg_sumberpasien" value="Umum" >
                                        <span><i></i>Umum</span>
                                    </label>
                                    <!-- <label class="form-check-label">
                                        <input class="form-check-input" type="radio" v-model="form.reg_sumberpasien"
                                            value="Umum" />
                                        Umum</label> -->
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="fancy-radio custom-color-green m-0 w-100">
                                        <input type="radio" v-model="form.reg_sumberpasien" value="Other" >
                                        <span><i></i>Isian</span>
                                    </label>
                                    <!-- <label class="form-check-label">
                                        <input class="form-check-input" type="radio" v-model="form.reg_sumberpasien"
                                            value="Other" />
                                        Isian</label> -->
                                </div>
                                <has-error :form="form" field="reg_sumberpasien" />
                            </div>
                        </div>
                        <div class="form-group row mt-4" v-if="form.reg_sumberpasien=='Other'">
                            <label for="" class="col-md-3 col-lg-2"></label>
                            <div class="col-md-8 col-lg-6">
                                <input type="text" name="reg_sumberpasien_isian" 
                                    placeholder="Ketikkan Kategori" id="" class="form-control" 
                                    v-model="form.reg_sumberpasien_isian" required>
                            </div>
                        </div>

                         <div class="form-group row mt-4">
                            <div class="col-md-3 col-lg-2">
                                <label for="">Hasil RDT</label>
                            </div>
                            <div class="col-md-3">
                                <select name="hasil_rdt" class="form-control" v-model="form.reg_hasil_rdt">
                                    <option value="Reaktif">Reaktif</option>
                                    <option value="Non Reaktif">Non Reaktif</option>
                                    <option value="Belum Test">Belum Test</option>
                                </select>
                            </div>
                        </div>

                        <hr>
                        <h4 class="mb-1 mt-0">
                            Identitas Pasien
                        </h4>
                        <p>Lengkapi Form dengan Identitas Pasien</p>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Nama Pasien
                                <span style="color:red">*</span>
                            </label>
                            <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_nama_pasien') }">
                                <input class="form-control" type="text" name="reg_nama_pasien" placeholder="" required
                                    v-model="form.reg_nama_pasien" />
                                <has-error :form="form" field="reg_nama_pasien" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                NIK
                            </label>
                            <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_nik') }">
                                <input class="form-control" type="text" name="reg_nik" placeholder=""
                                    v-model="form.reg_nik" maxlength="16"/>
                                <has-error :form="form" field="reg_nik" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Tempat Lahir
                            </label>
                            <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_tempatlahir') }">
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
                                 <dropdown-datepicker 
                                    v-model="form.reg_tgllahir"
                                    :minYear="1900"
                                    :daySuffixes="false"
                                    :maxYear="(new Date).getFullYear()"
                                    displayFormat="dmy"
                                    sortYear="asc"
                                    :default-date="form.reg_tgllahir"
                                    ref="tgl_lahir"
                                    :wrapper-class="form.errors.has('reg_tgllahir') ? 'is-invalid' : ''"
                                ></dropdown-datepicker>
                                <has-error :form="form" field="reg_tgllahir" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Jenis Kelamin
                                <span style="color:red">*</span>
                            </label>
                            <div class="col-md-8 col-lg-6">
                                <div class="form-check form-check-inline"
                                    :class="{ 'is-invalid': form.errors.has('reg_jk') }">
                                    <label class="fancy-radio custom-color-green m-0 w-100">
                                        <input type="radio" v-model="form.reg_jk" value="L"  >
                                        <span><i></i>Laki-laki</span>
                                    </label>
                                    <!-- <label class="form-check-label">
                                        <input class="form-check-input" type="radio" v-model="form.reg_jk"
                                            value="L" />
                                        Laki-laki</label> -->
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="fancy-radio custom-color-green m-0 w-100">
                                        <input type="radio" v-model="form.reg_jk" value="P">
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
                                <span style="color:red">*</span>
                            </label>
                            <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_nohp') }">
                                <input class="form-control" type="text" name="reg_nohp" placeholder=""
                                    v-model="form.reg_nohp" />
                                <has-error :form="form" field="reg_nohp" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Kota / Kabupaten
                                <span style="color:red">*</span>
                            </label>
                            <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_kota') }">
                                <select class="form-control" type="text" name="reg_kota" placeholder="" required
                                    v-model="form.reg_kota">
                                    <option :value="item.id" :key="idx" v-for="(item,idx) in optionKota">{{item.nama}}
                                    </option>
                                </select>
                                <has-error :form="form" field="reg_kota" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Kecamatan
                            </label>
                            <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_kecamatan') }">
                                <!-- <select class="form-control" type="text" name="reg_kecamatan" placeholder=""
                                    required v-model="form.reg_kecamatan" >
                                    <option :value="item.id" :key="idx" v-for="(item,idx) in optionKecamatan">{{item.nama}}</option>
                                </select> -->
                                <input v-model="form.reg_kecamatan" class="form-control" type="text"
                                    name="reg_kecamatan" />
                                <has-error :form="form" field="reg_kecamatan" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Kelurahan / Desa
                            </label>
                            <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_kelurahan') }">
                                <!-- <select class="form-control" type="text" name="reg_kelurahan" placeholder=""
                                    required v-model="form.reg_kelurahan" >
                                    <option :value="item.id" :key="idx" v-for="(item,idx) in optionKelurahan">{{item.nama}}</option>
                                </select> -->
                                <input class="multisteps-form__input form-control" type="text" name="reg_kelurahan"
                                 v-model="form.reg_kelurahan" />
                                <has-error :form="form" field="reg_kelurahan" />
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Alamat
                                <span style="color:red">*</span>
                            </label>
                            <div class="col-md-8 col-lg-6" :class="{ 'is-invalid': form.errors.has('reg_alamat') }">
                                <textarea class="multisteps-form__input form-control" type="text" name="reg_alamat"
                                    required v-model="form.reg_alamat"></textarea>
                                <has-error :form="form" field="reg_alamat" />
                            </div>
                        </div>

                        <div class="row mt-2">
                            <label class="col-md-3 col-lg-2">
                                RT / RW
                            </label>
                            <div class="input-group col-md-3 col-lg-2" :class="{ 'is-invalid':form.errors.has('reg_rt') }">
                                <span class="input-group-addon bootstrap-touchspin-postfix input-group-append">
                                    <span class="input-group-text">RT </span>
                                </span>
                                <input class="form-control" type="text" name="reg_rt" v-model="form.reg_rt" />
                            </div>
                            <div class="input-group col-md-3 col-lg-2" :class="{ 'is-invalid':form.errors.has('reg_rw') }">
                                <div class="input-group-addon bootstrap-touchspin-postfix input-group-append">
                                    <span class="input-group-text">RW </span>
                                </div>
                                <input class="form-control" type="text" name="reg_domisilirw"
                                    v-model="form.reg_rw" />
                            </div>
                            <has-error :form="form" field="reg_rt" />
                            <has-error :form="form" field="reg_rw" />
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">
                                Suhu
                            </label>
                            <div class="col-md-8 col-lg-6">
                                <input class="form-control" type="text" v-model="form.reg_suhu " placeholder=""
                                    :class="{ 'is-invalid': form.errors.has('reg_suhu') }" />
                                <has-error :form="form" field="reg_suhu" />
                            </div>
                        </div>
                        <hr>
                        <h4 class="mb-1 mt-0">
                            Identitas Sampel
                        </h4>

                        <div class="form-group row" v-for="(item,idx) in form.reg_sampel" :key="idx">
                            <label class="col-md-3 col-lg-2 col-form-label" for="sampel">Nomor Sampel {{idx+1}}</label>
                            <div class="col-md-4">
                                <input type="hidden" name="id_sampel" v-model="item.id" />
                                <input class="form-control" type="text" name="pen_sampel" v-model="item.nomor"
                                    :class="{ 'is-invalid':form.errors.has(`reg_sampel.${idx}`) }" />
                            </div>
                            <div class="col-md-3 col-lg-2 mt-2">
                                <span>
                                    <button class="btn btn-danger btn-xs" type="button"
                                        v-show="idx || ( !idx && form.reg_sampel.length > 1)">
                                        <i class="fa fa-minus-circle" @click="removeSample(idx)"></i></button>
                                    <button class="btn btn-success btn-xs" type="button" @click="addSample(idx)"
                                        v-show="idx == form.reg_sampel.length-1"><i
                                            class="fa fa-plus-circle"></i></button>
                                </span>
                            </div>
                            <has-error :form="form" :field="`reg_sampel.${idx}`" />
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


                        <!-- Bagian Gejala
                        <hr>
                        <h4 class="mb-1 mt-0">Gejala</h4>
                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">Tanggal onset gejala (panas)</label>
                            <div class="col-md-8 col-lg-6">
                                <date-picker placeholder="Tanggal Onset Panas" format="d MMMM yyyy"
                                    input-class="form-control" :monday-first="true"
                                    :wrapper-class="{ 'is-invalid': form.errors.has('reg_onset_panas') }"
                                    v-model="form.reg_onset_panas" />
                            </div>
                        </div>
                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">Panas</label>
                            <div class="col-md-8 col-lg-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejpanasya" name="reg_gejpanas"
                                        value="Ya" v-model="form.reg_gejpanas">
                                    <label class="form-check-label" for="gejpanasya">Ya</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejpanasno" name="reg_gejpanas"
                                        value="Tidak" v-model="form.reg_gejpanas">
                                    <label class="form-check-label" for="gejpanasno">Tidak</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejpanasnull" name="reg_gejpanas"
                                        value="Tidak Diisi" v-model="form.reg_gejpanas">
                                    <label class="form-check-label" for="gejpanasnull">Tidak
                                        Diisi</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">Tanda Pneumonia</label>
                            <div class="col-md-8 col-lg-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejpneumoniaya"
                                        name="reg_gejpenumonia" value="Ya" v-model="form.reg_gejpenumonia">
                                    <label class="form-check-label" for="gejpneumoniaya">Ya</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejpneumoniano"
                                        name="reg_gejpenumonia" value="Tidak" v-model="form.reg_gejpenumonia">
                                    <label class="form-check-label" for="gejpneumoniano">Tidak</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejpneumonianull"
                                        name="reg_gejpenumonia" value="Tidak Diisi" v-model="form.reg_gejpenumonia">
                                    <label class="form-check-label" for="gejpneumonianull">Tidak
                                        Diisi</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">Batuk</label>
                            <div class="col-md-8 col-lg-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejbatukya" name="reg_gejbatuk"
                                        value="Ya" v-model="form.reg_gejbatuk">
                                    <label class="form-check-label" for="gejbatukya">Ya</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejbatukno" name="reg_gejbatuk"
                                        value="Tidak" v-model="form.reg_gejbatuk">
                                    <label class="form-check-label" for="gejbatukno">Tidak</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejbatuknull" name="reg_gejbatuk"
                                        value="Tidak Diisi" v-model="form.reg_gejbatuk">
                                    <label class="form-check-label" for="gejbatuknull">Tidak
                                        Diisi</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">Nyeri Tenggorokan</label>
                            <div class="col-md-8 col-lg-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejnyeritenggorokanya"
                                        name="reg_gejnyeritenggorokan" value="Ya" v-model="form.reg_gejnyeritenggorokan">
                                    <label class="form-check-label" for="gejnyeritenggorokanya">Ya</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejnyeritenggorokanno"
                                        name="reg_gejnyeritenggorokan" value="Tidak" v-model="form.reg_gejnyeritenggorokan">
                                    <label class="form-check-label" for="gejnyeritenggorokanno">Tidak</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejnyeritenggorokannull"
                                        name="reg_gejnyeritenggorokan" value="Tidak Diisi" v-model="form.reg_gejnyeritenggorokan">
                                    <label class="form-check-label" for="gejnyeritenggorokannull">Tidak
                                        Diisi</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">Sesak Nafas</label>
                            <div class="col-md-8 col-lg-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejsesaknafasya"
                                        name="reg_gejsesaknafas" value="Ya" v-model="form.reg_gejsesaknafas">
                                    <label class="form-check-label" for="gejsesaknafasya">Ya</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejsesaknafasno"
                                        name="reg_gejsesaknafas" value="Tidak" v-model="form.reg_gejsesaknafas">
                                    <label class="form-check-label" for="gejsesaknafasno">Tidak</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejsesaknafasnull"
                                        name="reg_gejsesaknafas" value="Tidak Diisi" v-model="form.reg_gejsesaknafas">
                                    <label class="form-check-label" for="gejsesaknafasnull">Tidak
                                        Diisi</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">Pilek</label>
                            <div class="col-md-8 col-lg-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejpilekya" name="reg_gejpilek"
                                        value="Ya" v-model="form.reg_gejpilek">
                                    <label class="form-check-label" for="gejpilekya">Ya</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejpilekno" name="reg_gejpilek"
                                        value="Tidak" v-model="form.reg_gejpilek">
                                    <label class="form-check-label" for="gejpilekno">Tidak</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejpileknull" name="reg_gejpilek"
                                        value="Tidak Disii" v-model="form.reg_gejpilek">
                                    <label class="form-check-label" for="gejpileknull">Tidak
                                        Diisi</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">Lesu</label>
                            <div class="col-md-8 col-lg-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejlesuya" name="reg_gejlesu"
                                        value="Ya" v-model="form.reg_gejlesu">
                                    <label class="form-check-label" for="gejlesuya">Ya</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejlesuno" name="reg_gejlesu"
                                        value="Tidak" v-model="form.reg_gejlesu">
                                    <label class="form-check-label" for="gejlesuno">Tidak</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejlesunull" name="reg_gejlesu"
                                        value="Tidak Diisi" v-model="form.reg_gejlesu">
                                    <label class="form-check-label" for="gejlesunull">Tidak
                                        Diisi</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">Sakit Kepala</label>
                            <div class="col-md-8 col-lg-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejsakitkepalaya"
                                        name="reg_gejsakitkepala" value="Ya" v-model="form.reg_gejsakitkepala">
                                    <label class="form-check-label" for="gejsakitkepalaya">Ya</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejsakitkepalano"
                                        name="reg_gejsakitkepala" value="Tidak" v-model="form.reg_gejsakitkepala">
                                    <label class="form-check-label" for="gejsakitkepalano">Tidak</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejsakitkepalanull"
                                        name="reg_gejsakitkepala" value="Tidak Diisi" v-model="form.reg_gejsakitkepala">
                                    <label class="form-check-label" for="gejsakitkepalanull">Tidak
                                        Diisi</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">Diare</label>
                            <div class="col-md-8 col-lg-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejdiareya" name="reg_gejdiare"
                                        value="Ya" v-model="form.reg_gejdiare">
                                    <label class="form-check-label" for="gejdiareya">Ya</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejdiareno" name="reg_gejdiare"
                                        value="Tidak" v-model="form.reg_gejdiare">
                                    <label class="form-check-label" for="gejdiareno">Tidak</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejdiarenull" name="reg_gejdiare"
                                        value="Tidak Diisi" v-model="form.reg_gejdiare">
                                    <label class="form-check-label" for="gejdiarenull">Tidak
                                        Diisi</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mt-4">
                            <label class="col-md-3 col-lg-2">Mual/Muntah</label>
                            <div class="col-md-8 col-lg-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejmualmuntahya"
                                        name="reg_gejmualmuntah" value="Ya" v-model="form.reg_gejmualmuntah">
                                    <label class="form-check-label" for="gejmualmuntahya">Ya</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejmualmuntahno"
                                        name="reg_gejmualmuntah" value="Tidak" v-model="form.reg_gejmualmuntah">
                                    <label class="form-check-label" for="gejmualmuntahno">Tidak</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="gejmualmuntahnull"
                                        name="reg_gejmualmuntah" value="Tidak" v-model="form.reg_gejmualmuntah">
                                    <label class="form-check-label" for="gejmualmuntahnull">Tidak
                                        Diisi</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-lg-2 col-form-label" for="gejlain">Gejala Lainnya
                                (jelaskan)</label>
                            <div class="col-md-10">
                                <textarea class="form-control" rows="3" name="reg_gejlain" id="gejlain" v-model="form.reg_gejlain"></textarea>
                            </div>
                        </div> -->

                        <div class="form-group row mt-4 text-center justify-content-center">
                            <v-button :loading="form.busy" class="btn btn-md btn-primary block  m-b">
                                <i class="fa fa-save"></i> Update Data Register
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

    export default {
        middleware: "auth",
        async asyncData({route, store}) {
        let error = false;
        let resp = await axios.get("/v1/register/mandiri/"+route.params.register_id+"/"+route.params.pasien_id);
        let data = resp.data;
            return {
                form: new Form({
                    reg_no: data.reg_no,
                    reg_kewarganegaraan: data.reg_kewarganegaraan,
                    reg_sumberpasien: data.reg_sumberpasien,
                    reg_nama_pasien: data.reg_nama_pasien,
                    reg_nik: data.reg_nik,
                    reg_tempatlahir: data.reg_tempatlahir,
                    reg_tgllahir: data.reg_tgllahir,
                    reg_nohp: data.reg_nohp,
                    reg_kota: data.reg_kota,
                    reg_kecamatan: data.reg_kecamatan,
                    reg_kelurahan: data.reg_kelurahan,
                    reg_alamat: data.reg_alamat,
                    reg_rt: data.reg_rt,
                    reg_rw: data.reg_rw,
                    reg_suhu: data.reg_suhu,
                    reg_sampel: data.reg_sampel!=null?data.reg_sampel:[{id:null,nomor:null}],
                    reg_keterangan: data.reg_keterangan,
                    reg_jk : data.reg_jk,
                    reg_gejpanas:null,
                    reg_gejpenumonia:null,
                    reg_gejbatuk:null,
                    reg_gejnyeritenggorokan:null,
                    reg_gejsesaknafas:null,
                    reg_gejpilek:null,
                    reg_gejlesu:null,
                    reg_gejsakitkepala:null,
                    reg_gejdiare:null,
                    reg_gejmualmuntah:null,
                    reg_gejlain:null,
                    reg_hasil_rdt:data.reg_hasil_rdt,
                    reg_usia_tahun:data.reg_usia_tahun,
                    reg_usia_bulan:data.reg_usia_bulan,
                    reg_sumberpasien_isian:data.reg_sumberpasien_isian

                }),
                selected_reg: {},
                optionKota: [],
                optionKecamatan: [],
                optionKelurahan: [],
            };
        },
        methods: {
            async getNoreg() {
                let resp = await axios.get('/v1/register/noreg')
                this.form.reg_no = resp.data
            },
            initForm() {
               this.form =  new Form({
                    reg_no: null,
                    reg_kewarganegaraan: null,
                    reg_sumberpasien: null,
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
                    reg_suhu: null,
                    reg_sampel: [{
                        nomor: null
                    }],
                    reg_keterangan: null,
                    reg_gejpanas:null,
                    reg_gejpenumonia:null,
                    reg_gejbatuk:null,
                    reg_gejnyeritenggorokan:null,
                    reg_gejsesaknafas:null,
                    reg_gejpilek:null,
                    reg_gejlesu:null,
                    reg_gejsakitkepala:null,
                    reg_gejdiare:null,
                    reg_gejmualmuntah:null,
                    reg_gejlain:null
                })
            },
            addSample() {
                // console.log(this.form.reg_sampel)
                this.form.reg_sampel.push({
                    nomor: null,
                    id:null,
                })
            },
            async removeSample(index) {
                const sample = this.form.reg_sampel[index];
                if(sample.id!=null) {
                    await axios.get('/v1/register/delete-sampel/'+sample.id)
                }
                this.form.reg_sampel.splice(index, 1)
            },
            async getKota() {
                const resp = await axios.get('/v1/list-kota-jabar');
                this.optionKota = resp.data;
            },
            async submit() {
                // Submit the form.
                try { 
                    const response = await this.form.post("/v1/register/mandiri/update/"+this.$route.params.register_id+'/'+this.$route.params.pasien_id);
                    // this.$toast.success(response.data.message, {
                    //     icon: 'check',
                    //     iconPack: 'fontawesome',
                    //     duration: 5000
                    // })
                    this.$swal.fire("Berhasil Ubah Data", "Data Pasien Register Berhasil Diubah", "success");
                    // console.log('Response : ', response);
                    // this.initForm();
                    // this.getNoreg();
                    this.$router.push('/registrasi/mandiri');
                } catch (err) {
                    console.log(err);
                    if (err.response && err.response.data.code == 422) {
                        this.$nextTick(() => {
                            console.log(err.response.data.error)
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
            },
            async getUpdate(){
                const resp = await axios.get(`/v1/register/mandiri/${this.registerId}/${this.pasienId}`)
                this.data = resp.data;
                console.log(resp);
                // this.form = resp.data;
            }
        },
        head() {
            return {
                title: "Pengambilan / Penerimaan Sampel Baru"
            };
        },
        created() {
            this.getKota()
            this.getUpdate()
            // this.getNoreg();
        },
        watch: {
            "form.reg_kota": function (newVal, oldVal) {

            },
             "form.reg_nik": function (newVal, oldVal) {
                if (newVal && newVal.length >= 12) {
                    let dd = parseInt(newVal.substr(6,2))
                    if (dd >= 40) {
                        this.form.reg_jk = 'P'
                        dd -= 40
                    } else {
                        this.form.reg_jk = 'L'
                    }
                    let mm = parseInt(newVal.substr(8,2))
                    let yy = parseInt(newVal.substr(10,2))
                    if (yy <= 30) {
                        let str = '' + (2000+yy) +'-'+ ('' + mm).padStart(2,'0') +'-'+ ('' + dd).padStart(2,'0')
                        this.form.reg_tgllahir = str
                        this.nik_tgl = str
                    } else {
                        let str = '' + (1900+yy) +'-'+ ('' + mm).padStart(2,'0') +'-'+ ('' + dd).padStart(2,'0')
                        this.form.reg_tgllahir = str
                        this.nik_tgl = str
                    }
                    this.$nextTick(() => {
                        this.$refs.tgl_lahir.init();
                    })
                }
            },
            "form.reg_tgllahir": function(newVal, oldVal) {
                var birthday = new Date(this.form.reg_tgllahir)
                var now = new Date();
                var yearNow = now.getYear();
                var monthNow = now.getMonth() + 1;
                var dayNow = now.getDate();

                var yearDob = birthday.getYear();
                var monthDob = birthday.getMonth() + 1;
                var dayDob = birthday.getDate();
                var second = 1000;
                var minute = second*60;
                var hour = minute*60;
                var day = hour*24;
                var month = day*30; 
                var year = day*365;
                var ms = now - birthday;
                var msb = Math.round(ms % year)
                this.form.reg_usia_tahun = Math.round(ms / year)
                this.form.reg_usia_bulan = Math.round(msb / month)
            }
        },
        computed:{
            registerId(){
                return this.$route.params.register_id
            },
            pasienId(){
                return this.$route.params.pasien_id
            } 
        }
    };
</script>