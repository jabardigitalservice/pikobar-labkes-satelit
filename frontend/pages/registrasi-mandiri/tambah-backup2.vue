<template>
    <div class="wrapper wrapper-content">
        <portal to="title-name">
            Registrasi Pasien
        </portal>
        <portal to="title-action">
            <div class="title-action">
                <nuxt-link to="/registrasi/mandiri" class="btn btn-primary">Kembali</nuxt-link>
            </div>
        </portal>
        <div class="row">
            <div class="col-lg-12">
                <Ibox title="Registrasi Mandiri">
                    <form @submit.prevent="submitForm" @keydown="form.onKeydown($event)">
                        <div id="smartwizard-default">
                            <ul>
                                <li><a href="#sw-default-step-1">Identitas Pasien<small class="d-block">Informasi
                                            Pasien</small></a></li>
                                <li><a href="#sw-default-step-2">Tanda dan Gejala<small class="d-block">Gejala
                                            Pasien</small></a></li>
                                <li><a href="#sw-default-step-3">Pemeriksaan Penunjang<small class="d-block">Pemeriksaan
                                            Pasien</small></a></li>
                                <li><a href="#sw-default-step-4">Riwayat Kontak<small class="d-block">Riwayat Kontak
                                            Pasien</small></a></li>
                                <li><a href="#sw-default-step-5">Penyakit Penyerta<small class="d-block">Komorbid
                                            Pasien</small></a></li>
                                <li><a href="#sw-default-step-6">Sampel<small class="d-block">Nomor
                                            Sampel</small></a></li>
                            </ul>
                            <div class="p-3">
                                <div id="sw-default-step-1">
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Nomor Registrasi <span
                                                style="color:red;">*</span></label>
                                        <div class="col-md-6" :class="{ 'is-invalid': form.errors.has('nomor_register') }">
                                            <input class="multisteps-form__input form-control" type="text" name="reg_no"
                                                placeholder="Nomor Registrasi" required v-model="form.nomor_register" />
                                            <has-error :form="form" field="nomor_register" />
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Kewarganegaraan <span
                                                style="color:red;">*</span></label>
                                        <div class="col-md-6">
                                            <select class="multisteps-form__input form-control col-md-6"
                                                name="reg_kewarganegaraan" required 
                                                :class="{ 'is-invalid': form.errors.has('kewarganegaraan') }">
                                                <option value="WNI">WNI</option>
                                                <option value="WNA">WNA</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Nama Pasien <span style="color:red;">*</span></label>
                                        <div class="col-md-6">
                                            <input class="multisteps-form__input form-control" type="text"
                                                name="reg_nama_pasien" placeholder="Nama Lengkap Pasien" required />
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Nomor Identitas <span
                                                style="color:red;">*</span></label>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" id="selectktpid" type="radio"
                                                    name="reg_jenisidentitas" value="KTP" onclick="ktpselect();"
                                                    required>
                                                <label class="form-check-label" for="selectktpid">Nomor Induk
                                                    Kependudukan</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" id="selectsimid" type="radio"
                                                    name="reg_jenisidentitas" value="SIM" onclick="simselect();">
                                                <label class="form-check-label" for="selectsimid">Nomor Sim</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4" id="sim" style="display: none;">
                                        <div class="form-group row">
                                            <label class="col-md-2">Nomor SIM (Surat Izin Mengemudi)</label>
                                            <div class="col-md-6">
                                                <input class="multisteps-form__input form-control" type="text"
                                                    id="idsim" name="reg_nosim" maxlength="12"
                                                    placeholder="Nomor SIM Pasien" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4" id="ktp" style="display: none;">
                                        <div class="form-group row">
                                            <label class="col-md-2">NIK KTP (Nomor Induk Kependudukan)</label>
                                            <div class="col-md-6">
                                                <input class="multisteps-form__input form-control" type="text"
                                                    id="idktp" name="reg_nik" maxlength="16" placeholder="NIK Pasien" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Nomor Kartu Keluarga </label>
                                        <div class="col-md-6">
                                            <input class="multisteps-form__input form-control" type="text"
                                                name="reg_nkk" placeholder="Nomor Kartu Keluarga" />
                                        </div>
                                    </div>

                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Tanggal Lahir <span style="color:red;">*</span><br>
                                            <small>Format : yyyy/mm/dd (contoh : 2020/12/01)</small></label>
                                        <div class="col-md-6">
                                            <input class="multisteps-form__input form-control" id="tanggallahir"
                                                type="text" name="reg_tanggallahir" placeholder="Tanggal Lahir"
                                                required />
                                        </div>
                                    </div>

                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Tempat Lahir <span style="color:red;">*</span></label>
                                        <div class="col-md-6">
                                            <input class="multisteps-form__input form-control" type="text"
                                                name="reg_tempatlahir" placeholder="Tempat Lahir" required />
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Pekerjaan </label>
                                        <div class="col-md-6">
                                            <input class="multisteps-form__input form-control" type="text"
                                                name="reg_pekerjaan" placeholder="Pekerjaan" />
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Jenis Kelamin <span style="color:red;">*</span></label>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" id="kellaki" type="radio"
                                                    name="reg_kelamin" value="Laki Laki" onclick="show1();" required>
                                                <label class="form-check-label" for="kellaki">Laki Laki</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" id="kelperempuan" type="radio"
                                                    name="reg_kelamin" value="Perempuan" onclick="show2();">
                                                <label class="form-check-label" for="kelperempuan">Perempuan</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4" id="ifcewe" style="display: none;">
                                        <div class="form-group row">
                                            <label class="col-md-2">Bila perempuan, apakah hamil atau pasca
                                                melahirkan?</label>
                                            <div class="col-md-6">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" id="pascaya"
                                                        name="reg_hamil_pasca" value="Ya">
                                                    <label class="form-check-label" for="pascaya">Ya</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" id="pascano"
                                                        name="reg_hamil_pasca" value="Tidak">
                                                    <label class="form-check-label" for="pascano">Tidak</label>
                                                </div>
                                            </div>
                                        </div> <!-- -->
                                    </div>

                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Alamat <span style="color:red;">*</span></label>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <select class="multisteps-form__input col-md-3 form-control"
                                                    id="domisilikotakab" name="reg_domisilikotakab" required>
                                                    <option value="Kota Bandung">Kota Bandung</option>
                                                    <option value="Kabupaten Bandung">Kabupaten Bandung</option>
                                                    <option value="Kabupaten Bandung Barat">Kabupaten Bandung Barat
                                                    </option>
                                                    <option value="Kota Banjar">Kota Banjar</option>
                                                    <option value="Kota Cimahi">Kota Cimahi</option>
                                                    <option value="Kabupaten Bekasi">Kabupaten Bekasi</option>
                                                    <option value="Kota Bekasi">Kota Bekasi</option>
                                                    <option value="Kabupaten Bogor">Kabupaten Bogor</option>
                                                    <option value="Kota Bogor">Kota Bogor</option>
                                                    <option value="Kabupaten Ciamis">Kabupaten Ciamis</option>
                                                    <option value="Kabupaten Cianjur">Kabupaten Cianjur</option>
                                                    <option value="Kabupaten Cirebon">Kabupaten Cirebon</option>
                                                    <option value="Kota Cirebon">Kota Cirebon</option>
                                                    <option value="Kota Depok">Kota Depok</option>
                                                    <option value="Kabupaten Garut">Kabupaten Garut</option>
                                                    <option value="Kabupaten Indramayu">Kabupaten Indramayu</option>
                                                    <option value="Kabupaten Karawang">Kabupaten Karawang</option>
                                                    <option value="Kabupaten Kuningan">Kabupaten Kuningan</option>
                                                    <option value="Kabupaten Majalengka">Kabupaten Majalengka
                                                    </option>
                                                    <option value="Kabupaten Pangandaran">Kabupaten Pangandaran
                                                    </option>
                                                    <option value="Kabupaten Purwakarta">Kabupaten Purwakarta
                                                    </option>
                                                    <option value="Kabupaten Subang">Kabupaten Subang</option>
                                                    <option value="Kabupaten Sukabumi">Kabupaten Sukabumi</option>
                                                    <option value="Kota Sukabumi">Kota Sukabumi</option>
                                                    <option value="Kabupaten Sumedang">Kabupaten Sumedang</option>
                                                    <option value="Kota Tasikmalaya">Kota Tasikmalaya</option>
                                                    <option value="Kabupaten Tasikmalaya">Kabupaten Tasikmalaya
                                                    </option>
                                                </select>
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        <div class="input-group-preppend">
                                                            <span class="input-group-text">Alamat <span
                                                                    style="color:red;">*</span></span>
                                                        </div>
                                                        <input class="multisteps-form__input form-control" type="text"
                                                            name="reg_alamat" placeholder="Alamat Pasien" required />
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="input-group col-md-4">
                                                    <div class="input-group-preppend">
                                                        <span class="input-group-text">Kecamatan <span
                                                                style="color:red;">*</span></span>
                                                    </div>
                                                    <input class="multisteps-form__input form-control" type="text"
                                                        name="reg_domisilikecamatan" required />
                                                </div>
                                                <div class="input-group col-md-4">
                                                    <div class="input-group-preppend">
                                                        <span class="input-group-text">Kelurahan <span
                                                                style="color:red;">*</span></span>
                                                    </div>
                                                    <input class="multisteps-form__input form-control" type="text"
                                                        name="reg_domisilikelurahan" required />
                                                </div>
                                                <div class="input-group col-md-2">
                                                    <div class="input-group-preppend">
                                                        <span class="input-group-text">RT <span
                                                                style="color:red;">*</span></span>
                                                    </div>
                                                    <input class="multisteps-form__input form-control" type="text"
                                                        name="reg_domisilirt" required />
                                                </div>
                                                <div class="input-group col-md-2">
                                                    <div class="input-group-preppend">
                                                        <span class="input-group-text">RW <span
                                                                style="color:red;">*</span></span>
                                                    </div>
                                                    <input class="multisteps-form__input form-control" type="text"
                                                        name="reg_domisilirw" required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Nomor Telp/HP <span style="color:red;">*</span></label>
                                        <div class="col-md-6">
                                            <input class="multisteps-form__input form-control" type="text"
                                                name="reg_notelp_pasien" placeholder="Nomor Telp/HP Pasien / Keluarga"
                                                required />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Keterangan lain</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" rows="3"
                                                name="reg_keteranganpasien">Keterangan lain</textarea>
                                        </div>
                                    </div>


                                    <hr>
                                    <h4 class="mb-1 mt-0">Riwayat Kunjungan</h4>
                                    <p>Isi pada baris yang merupakan kali kunjungan saat ini.</p>
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Kunjungan Ke <span style="color:red;">*</span></label>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="kunke1"
                                                    name="reg_kunke" value="1" required>
                                                <label class="form-check-label" for="kunke1">Ke-1</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="kunke2"
                                                    name="reg_kunke" value="2">
                                                <label class="form-check-label" for="kunke2">Ke-2</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="kunke3"
                                                    name="reg_kunke" value="3">
                                                <label class="form-check-label" for="kunke3">Ke-3</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="kunke4"
                                                    name="reg_kunke" value="4">
                                                <label class="form-check-label" for="kunke4">Ke-4</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="kunke5"
                                                    name="reg_kunke" value="5">
                                                <label class="form-check-label" for="kunke5">Ke-5</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="kunke6"
                                                    name="reg_kunke" value="6">
                                                <label class="form-check-label" for="kunke6">Ke-6</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="kunke7"
                                                    name="reg_kunke" value="7">
                                                <label class="form-check-label" for="kunke7">Ke-7</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-4">
                                        <div class="col-md-4">
                                            <label>Tanggal Kunjungan</label>
                                            <input class="multisteps-form__input form-control" type="text"
                                                id="tanggalkunjungan" name="reg_tanggalkunjungan"
                                                placeholder="Tanggal Kunjungan" />

                                        </div>
                                        <div class="col-md-6">
                                            <label>Rumah Sakit / Fasyankes</label>
                                            <input class="multisteps-form__input form-control" type="text"
                                                name="reg_rsfasyankes" placeholder="Nama RS/Fasyankes" />
                                        </div>
                                    </div>

                                </div>
                                <div id="sw-default-step-2">
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Pernah tes RDT sebelumnya / Pasien dengan sampel RDT
                                            ?</label>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="pernahrdt"
                                                    name="rar_pernah_rdt" value="Ya" onclick="showRDT2();">
                                                <label class="form-check-label" for="pernahrdt">Ya</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="tidakpernahrdt"
                                                    name="rar_pernah_rdt" value="Tidak" onclick="showRDT();">
                                                <label class="form-check-label" for="tidakpernahrdt">Tidak</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4" id="ifrdt" style="display: none;">
                                        <div class="form-group row">
                                            <label class="col-md-2">Hasil RDT Terakhir</label>
                                            <div class="col-md-10">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" id="reaktif"
                                                        name="rar_hasil_rdt" value="Ya">
                                                    <label class="form-check-label" for="reaktif">Reaktif</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" id="nonreaktif"
                                                        name="rar_hasil_rdt" value="Tidak">
                                                    <label class="form-check-label" for="nonreaktif">Non
                                                        Reaktif</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-2">Tanggal Pemeriksaan RDT</label>
                                            <div class="col-md-10">
                                                <input class="multisteps-form__input form-control" type="text"
                                                    id="tanggalrdt" name="rar_tanggal_rdt"
                                                    placeholder="Tanggal Pemeriksaan RDT" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-2 col-form-label">Keterangan RDT</label>
                                            <div class="col-md-10">
                                                <textarea class="form-control" rows="3"
                                                    name="rar_keterangan">isikan Jenis sampel dan keterangan penting lainnya</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Tanggal onset gejala (panas)</label>
                                        <div class="col-md-6">
                                            <input class="multisteps-form__input form-control" type="text"
                                                id="onsetpanas" name="reg_onset_panas"
                                                placeholder="Tanggal Onset Panas" />
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Panas</label>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="gejpanasya"
                                                    name="reg_gejpanas" value="Ya">
                                                <label class="form-check-label" for="gejpanasya">Ya</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="gejpanasno"
                                                    name="reg_gejpanas" value="Tidak">
                                                <label class="form-check-label" for="gejpanasno">Tidak</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="gejpanasnull"
                                                    name="reg_gejpanas" value="Tidak Diisi">
                                                <label class="form-check-label" for="gejpanasnull">Tidak
                                                    Diisi</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Tanda Pneumonia</label>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="gejpneumoniaya"
                                                    name="reg_gejpenumonia" value="Ya">
                                                <label class="form-check-label" for="gejpneumoniaya">Ya</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="gejpneumoniano"
                                                    name="reg_gejpenumonia" value="Tidak">
                                                <label class="form-check-label" for="gejpneumoniano">Tidak</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="gejpneumonianull"
                                                    name="reg_gejpenumonia" value="Tidak Diisi">
                                                <label class="form-check-label" for="gejpneumonianull">Tidak
                                                    Diisi</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Batuk</label>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="gejbatukya"
                                                    name="reg_gejbatuk" value="Ya">
                                                <label class="form-check-label" for="gejbatukya">Ya</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="gejbatukno"
                                                    name="reg_gejbatuk" value="Tidak">
                                                <label class="form-check-label" for="gejbatukno">Tidak</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="gejbatuknull"
                                                    name="reg_gejbatuk" value="Tidak Diisi">
                                                <label class="form-check-label" for="gejbatuknull">Tidak
                                                    Diisi</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Nyeri Tenggorokan</label>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="gejnyeritenggorokanya"
                                                    name="reg_gejnyeritenggorokan" value="Ya">
                                                <label class="form-check-label" for="gejnyeritenggorokanya">Ya</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="gejnyeritenggorokanno"
                                                    name="reg_gejnyeritenggorokan" value="Tidak">
                                                <label class="form-check-label"
                                                    for="gejnyeritenggorokanno">Tidak</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio"
                                                    id="gejnyeritenggorokannull" name="reg_gejnyeritenggorokan"
                                                    value="Tidak Diisi">
                                                <label class="form-check-label" for="gejnyeritenggorokannull">Tidak
                                                    Diisi</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Sesak Nafas</label>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="gejsesaknafasya"
                                                    name="reg_gejsesaknafas" value="Ya">
                                                <label class="form-check-label" for="gejsesaknafasya">Ya</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="gejsesaknafasno"
                                                    name="reg_gejsesaknafas" value="Tidak">
                                                <label class="form-check-label" for="gejsesaknafasno">Tidak</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="gejsesaknafasnull"
                                                    name="reg_gejsesaknafas" value="Tidak Diisi">
                                                <label class="form-check-label" for="gejsesaknafasnull">Tidak
                                                    Diisi</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Pilek</label>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="gejpilekya"
                                                    name="reg_gejpilek" value="Ya">
                                                <label class="form-check-label" for="gejpilekya">Ya</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="gejpilekno"
                                                    name="reg_gejpilek" value="Tidak">
                                                <label class="form-check-label" for="gejpilekno">Tidak</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="gejpileknull"
                                                    name="reg_gejpilek" value="Tidak Disii">
                                                <label class="form-check-label" for="gejpileknull">Tidak
                                                    Diisi</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Lesu</label>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="gejlesuya"
                                                    name="reg_gejlesu" value="Ya">
                                                <label class="form-check-label" for="gejlesuya">Ya</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="gejlesuno"
                                                    name="reg_gejlesu" value="Tidak">
                                                <label class="form-check-label" for="gejlesuno">Tidak</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="gejlesunull"
                                                    name="reg_gejlesu" value="Tidak Diisi">
                                                <label class="form-check-label" for="gejlesunull">Tidak
                                                    Diisi</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Sakit Kepala</label>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="gejsakitkepalaya"
                                                    name="reg_gejsakitkepala" value="Ya">
                                                <label class="form-check-label" for="gejsakitkepalaya">Ya</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="gejsakitkepalano"
                                                    name="reg_gejsakitkepala" value="Tidak">
                                                <label class="form-check-label" for="gejsakitkepalano">Tidak</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="gejsakitkepalanull"
                                                    name="reg_gejsakitkepala" value="Tidak Diisi">
                                                <label class="form-check-label" for="gejsakitkepalanull">Tidak
                                                    Diisi</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Diare</label>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="gejdiareya"
                                                    name="reg_gejdiare" value="Ya">
                                                <label class="form-check-label" for="gejdiareya">Ya</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="gejdiareno"
                                                    name="reg_gejdiare" value="Tidak">
                                                <label class="form-check-label" for="gejdiareno">Tidak</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="gejdiarenull"
                                                    name="reg_gejdiare" value="Tidak Diisi">
                                                <label class="form-check-label" for="gejdiarenull">Tidak
                                                    Diisi</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Mual/Muntah</label>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="gejmualmuntahya"
                                                    name="reg_gejmualmuntah" value="Ya">
                                                <label class="form-check-label" for="gejmualmuntahya">Ya</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="gejmualmuntahno"
                                                    name="reg_gejmualmuntah" value="Tidak">
                                                <label class="form-check-label" for="gejmualmuntahno">Tidak</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="gejmualmuntahnull"
                                                    name="reg_gejmualmuntah" value="Tidak">
                                                <label class="form-check-label" for="gejmualmuntahnull">Tidak
                                                    Diisi</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label" for="gejlain">Gejala Lainnya
                                            (jelaskan)</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" rows="3" name="reg_gejlain"
                                                id="gejlain"></textarea>
                                        </div>
                                    </div>

                                </div>
                                <div id="sw-default-step-3">
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">X-Ray Paru</label>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="xrayya"
                                                    name="reg_xrayparu" value="Ya">
                                                <label class="form-check-label" for="xrayya">Ya</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="xrayno"
                                                    name="reg_xrayparu" value="Tidak">
                                                <label class="form-check-label" for="xrayno">Tidak</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label" for="hasilxray">Hasil X-Ray
                                            Paru(jelaskan)</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" rows="3" name="reg_hasilxray"
                                                id="hasilxray"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Leukosit</label>
                                        <div class="input-group col-md-3">
                                            <input class=" form-control" type="number" min="0"
                                                name="reg_leukosit" />
                                            <div class="input-group-append">
                                                <span class="input-group-text">/ul</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Limfosit</label>
                                        <div class="input-group col-md-3">
                                            <input class="multisteps-form__input form-control" type="number" min="0"
                                                name="reg_limfosit" />
                                            <div class="input-group-append">
                                                <span class="input-group-text">/ul</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Trombosit</label>
                                        <div class="input-group col-md-3">
                                            <input class="multisteps-form__input form-control" type="number" min="0"
                                                name="reg_trombosit" />
                                            <div class="input-group-append">
                                                <span class="input-group-text">/ul</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Menggunakan Ventilator</label>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="ventilatorya"
                                                    name="reg_ventilator" value="Ya">
                                                <label class="form-check-label" for="ventilatorya">Ya</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="ventilatorno"
                                                    name="reg_ventilator" value="Tidak">
                                                <label class="form-check-label" for="ventilatorno">Tidak</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Status Kesehatan Pasien</label>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="pulang"
                                                    name="reg_statuskes" value="Pulang">
                                                <label class="form-check-label" for="pulang">Pulang</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="dirawat"
                                                    name="reg_statuskes" value="Dirawat">
                                                <label class="form-check-label" for="dirawat">Dirawat</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="meninggal"
                                                    name="reg_statuskes" value="Meninggal">
                                                <label class="form-check-label" for="meninggal">Meninggal</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Hasil lab lainnya :</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" rows="5"
                                                name="reg_hasillablainnya"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div id="sw-default-step-4">
                                    <div class="form-group row mt-4">
                                        <label class="col-md-4">Dalam 14 hari sebelum sakit, apakah pasien melakukan
                                            perjalanan ke luar negeri?</label>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="luarnegriya"
                                                    name="reg_luarnegri" value="Ya">
                                                <label class="form-check-label" for="luarnegriya">Ya</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="luarnegrino"
                                                    name="reg_luarnegri" value="Tidak">
                                                <label class="form-check-label" for="luarnegrino">Tidak</label>
                                            </div>
                                        </div>
                                    </div>
                                    <p><b>Jika Ya, urutkan berdasarkan tanggal kunjungan</b></p>
                                    <div class="form-group row mt-4">
                                        <div class="input-group col-md-4">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Tanggal Kunjungan ke 1</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                id="tanggalkunjungan_pasien1" name="reg_tanggalkunjungan_pasien1" />
                                        </div>
                                        <div class="input-group col-md-4">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Nama Kota</span>
                                            </div><input class="multisteps-form__input form-control" type="text"
                                                name="reg_kotakunjungan_pasien1" />
                                        </div>
                                        <div class="input-group col-md-4">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Nama Negara</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                name="reg_negarakunjungan_pasien1" />
                                        </div>
                                    </div>

                                    <div class="form-group row mt-4">
                                        <div class="input-group col-md-4">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Tanggal Kunjungan ke 2</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                id="tanggalkunjungan_pasien2" name="reg_tanggalkunjungan_pasien2" />
                                        </div>
                                        <div class="input-group col-md-4">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Nama Kota</span>
                                            </div><input class="multisteps-form__input form-control" type="text"
                                                name="reg_kotakunjungan_pasien2" />
                                        </div>
                                        <div class="input-group col-md-4">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Nama Negara</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                name="reg_negarakunjungan_pasien2" />
                                        </div>
                                    </div>

                                    <div class="form-group row mt-4">
                                        <div class="input-group col-md-4">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Tanggal Kunjungan ke 3</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                id="tanggalkunjungan_pasien3" name="reg_tanggalkunjungan_pasien3" />
                                        </div>
                                        <div class="input-group col-md-4">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Nama Kota</span>
                                            </div><input class="multisteps-form__input form-control" type="text"
                                                name="reg_kotakunjungan_pasien3" />
                                        </div>
                                        <div class="input-group col-md-4">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Nama Negara</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                name="reg_negarakunjungan_pasien3" />
                                        </div>
                                    </div>

                                    <div class="form-group row mt-4">
                                        <div class="input-group col-md-4">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Tanggal Kunjungan ke 4</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                id="tanggalkunjungan_pasien4" name="reg_tanggalkunjungan_pasien4" />
                                        </div>
                                        <div class="input-group col-md-4">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Nama Kota</span>
                                            </div><input class="multisteps-form__input form-control" type="text"
                                                name="reg_kotakunjungan_pasien4" />
                                        </div>
                                        <div class="input-group col-md-4">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Nama Negara</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                name="reg_negarakunjungan_pasien4" />
                                        </div>
                                    </div>

                                    <div class="form-group row mt-4">
                                        <div class="input-group col-md-4">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Tanggal Kunjungan ke 5</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                id="tanggalkunjungan_pasien5" name="reg_tanggalkunjungan_pasien5" />
                                        </div>
                                        <div class="input-group col-md-4">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Nama Kota</span>
                                            </div><input class="multisteps-form__input form-control" type="text"
                                                name="reg_kotakunjungan_pasien5" />
                                        </div>
                                        <div class="input-group col-md-4">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Nama Negara</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                name="reg_negarakunjungan_pasien5" />
                                        </div>
                                    </div>

                                    <div class="form-group row mt-4">
                                        <div class="input-group col-md-4">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Tanggal Kunjungan ke 6</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                id="tanggalkunjungan_pasien6" name="reg_tanggalkunjungan_pasien6" />
                                        </div>
                                        <div class="input-group col-md-4">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Nama Kota</span>
                                            </div><input class="multisteps-form__input form-control" type="text"
                                                name="reg_kotakunjungan_pasien6" />
                                        </div>
                                        <div class="input-group col-md-4">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Nama Negara</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                name="reg_negarakunjungan_pasien6" />
                                        </div>
                                    </div>


                                    <div class="form-group row mt-4">
                                        <div class="input-group col-md-4">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Tanggal Kunjungan ke 7</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                id="tanggalkunjungan_pasien7" name="reg_tanggalkunjungan_pasien7" />
                                        </div>
                                        <div class="input-group col-md-4">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Nama Kota</span>
                                            </div><input class="multisteps-form__input form-control" type="text"
                                                name="reg_kotakunjungan_pasien7" />
                                        </div>
                                        <div class="input-group col-md-4">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Nama Negara</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                name="reg_negarakunjungan_pasien7" />
                                        </div>
                                    </div>

                                    <div class="form-group row mt-4">
                                        <label class="col-md-4">Dalam 14 hari sebelum sakit, apakah pasien kontak
                                            dengan orang yang sakit?</label>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="kontakdgnsakitya"
                                                    name="reg_kontakdgnsakit" value="Ya">
                                                <label class="form-check-label" for="kontakdgnsakitya">Ya</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="kontakdgnsakitno"
                                                    name="reg_kontakdgnsakit" value="Tidak">
                                                <label class="form-check-label" for="kontakdgnsakitno">Tidak</label>
                                            </div>
                                        </div>
                                    </div>
                                    <p><b>Jika Ya, Tambahkan beberapa kontak terakhir dengan orang sakit</b></p>
                                    <div class="form-group row mt-4">
                                        <div class="input-group col-md-3">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Nama</span>
                                            </div><input class="multisteps-form__input form-control" type="text"
                                                name="reg_namakon1" />
                                        </div>
                                        <div class="input-group col-md-3">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Alamat</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                name="reg_alamatkon1" />
                                        </div>
                                        <div class="input-group col-md-2">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Hubungan</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                name="reg_hubungankon" />
                                        </div>
                                        <div class="input-group col-md-2">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Tanggal Pertama</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                id="tanggalkonawal1" name="reg_tanggalkonawal1" />
                                        </div>
                                        <div class="input-group col-md-2">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Tanggal Terakhir</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                id="tanggalkonakhir1" name="reg_tanggalkonakhir1" />
                                        </div>
                                    </div>

                                    <div class="form-group row mt-4">
                                        <div class="input-group col-md-3">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Nama</span>
                                            </div><input class="multisteps-form__input form-control" type="text"
                                                name="reg_namakon2" />
                                        </div>
                                        <div class="input-group col-md-3">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Alamat</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                name="reg_alamatkon2" />
                                        </div>
                                        <div class="input-group col-md-2">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Hubungan</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                name="reg_hubungankon2" />
                                        </div>
                                        <div class="input-group col-md-2">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Tanggal Pertama</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                id="tanggalkonawal2" name="reg_tanggalkonawal2" />
                                        </div>
                                        <div class="input-group col-md-2">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Tanggal Terakhir</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                id="tanggalkonakhir2" name="reg_tanggalkonakhir2" />
                                        </div>
                                    </div>

                                    <div class="form-group row mt-4">
                                        <div class="input-group col-md-3">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Nama</span>
                                            </div><input class="multisteps-form__input form-control" type="text"
                                                name="reg_namakon3" />
                                        </div>
                                        <div class="input-group col-md-3">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Alamat</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                name="reg_alamatkon3" />
                                        </div>
                                        <div class="input-group col-md-2">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Hubungan</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                name="reg_hubungankon3" />
                                        </div>
                                        <div class="input-group col-md-2">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Tanggal Pertama</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                id="tanggalkonawal3" name="reg_tanggalkonawal3" />
                                        </div>
                                        <div class="input-group col-md-2">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Tanggal Terakhir</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                id="tanggalkonakhir3" name="reg_tanggalkonakhir3" />
                                        </div>
                                    </div>

                                    <div class="form-group row mt-4">
                                        <div class="input-group col-md-3">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Nama</span>
                                            </div><input class="multisteps-form__input form-control" type="text"
                                                name="reg_namakon4" />
                                        </div>
                                        <div class="input-group col-md-3">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Alamat</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                name="reg_alamatkon4" />
                                        </div>
                                        <div class="input-group col-md-2">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Hubungan</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                name="reg_hubungankon4" />
                                        </div>
                                        <div class="input-group col-md-2">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Tanggal Pertama</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                id="tanggalkonawal4" name="reg_tanggalkonawal4" />
                                        </div>
                                        <div class="input-group col-md-2">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Tanggal Terakhir</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                id="tanggalkonakhir4" name="reg_tanggalkonakhir4" />
                                        </div>
                                    </div>

                                    <div class="form-group row mt-4">
                                        <div class="input-group col-md-3">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Nama</span>
                                            </div><input class="multisteps-form__input form-control" type="text"
                                                name="reg_namakon5" />
                                        </div>
                                        <div class="input-group col-md-3">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Alamat</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                name="reg_alamatkon5" />
                                        </div>
                                        <div class="input-group col-md-2">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Hubungan</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                name="reg_hubungankon5" />
                                        </div>
                                        <div class="input-group col-md-2">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Tanggal Pertama</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                id="tanggalkonawal5" name="reg_tanggalkonawal5" />
                                        </div>
                                        <div class="input-group col-md-2">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Tanggal Terakhir</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                id="tanggalkonakhir5" name="reg_tanggalkonakhir5" />
                                        </div>
                                    </div>

                                    <div class="form-group row mt-4">
                                        <div class="input-group col-md-3">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Nama</span>
                                            </div><input class="multisteps-form__input form-control" type="text"
                                                name="reg_namakon6" />
                                        </div>
                                        <div class="input-group col-md-3">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Alamat</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                name="reg_alamatkon6" />
                                        </div>
                                        <div class="input-group col-md-2">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Hubungan</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                name="reg_hubungankon6" />
                                        </div>
                                        <div class="input-group col-md-2">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Tanggal Pertama</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                id="tanggalkonawal6" name="reg_tanggalkonawal6" />
                                        </div>
                                        <div class="input-group col-md-2">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Tanggal Terakhir</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                id="tanggalkonakhir6" name="reg_tanggalkonakhir6" />
                                        </div>
                                    </div>


                                    <div class="form-group row mt-4">
                                        <div class="input-group col-md-3">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Nama</span>
                                            </div><input class="multisteps-form__input form-control" type="text"
                                                name="reg_namakon7" />
                                        </div>
                                        <div class="input-group col-md-3">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Alamat</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                name="reg_alamatkon7" />
                                        </div>
                                        <div class="input-group col-md-2">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Hubungan</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                name="reg_hubungankon7" />
                                        </div>
                                        <div class="input-group col-md-2">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Tanggal Pertama</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                id="tanggalkonawal7" name="reg_tanggalkonawal7" />
                                        </div>
                                        <div class="input-group col-md-2">
                                            <div class="input-group-preppend">
                                                <span class="input-group-text">Tanggal Terakhir</span>
                                            </div>
                                            <input class="multisteps-form__input form-control" type="text"
                                                id="tanggalkonakhir7" name="reg_tanggalkonakhir7" />
                                        </div>
                                    </div>

                                    <div class="form-group row mt-4">
                                        <label class="col-md-4">Apakah orang tersebut tersangka/terinfeksi
                                            Covid-19?</label>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="kontakterinfeksiya"
                                                    name="reg_kontakterinfeksi" value="Ya">
                                                <label class="form-check-label" for="kontakterinfeksiya">Ya</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="kontakterinfeksino"
                                                    name="reg_kontakterinfeksi" value="Tidak">
                                                <label class="form-check-label" for="kontakterinfeksino">Tidak</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <label class="col-md-4">Apakah ada anggota keluarga pasien yang sakitnya
                                            sama?</label>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="keluargapasienya"
                                                    name="reg_keluargapasiensakitsama" value="Ya">
                                                <label class="form-check-label" for="keluargapasienya">Ya</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="keluargapasienno"
                                                    name="reg_keluargapasiensakitsama" value="Tidak">
                                                <label class="form-check-label" for="keluargapasienno">Tidak</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div id="sw-default-step-5">

                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Penyakit Kardiovaskuler/Hipertensi</label>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="komorbidhipertensiya"
                                                    name="reg_komorbidhipertensi" value="Ya">
                                                <label class="form-check-label" for="komorbidhipertensiya">Ya</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="komorbidhipertensino"
                                                    name="reg_komorbidhipertensi" value="Tidak">
                                                <label class="form-check-label" for="komorbidhipertensino">Tidak</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="komorbidhipertensinull"
                                                    name="reg_komorbidhipertensi" value="Tidak Diisi">
                                                <label class="form-check-label" for="komorbidhipertensinull">Tidak
                                                    Diisi</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Diabetes Mellitus</label>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="komorbiddmya"
                                                    name="reg_komorbiddm" value="Ya">
                                                <label class="form-check-label" for="komorbiddmya">Ya</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="komorbiddmno"
                                                    name="reg_komorbiddm" value="Tidak">
                                                <label class="form-check-label" for="komorbiddmno">Tidak</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="komorbiddmnull"
                                                    name="reg_komorbiddm" value="Tidak Diisi">
                                                <label class="form-check-label" for="komorbiddmnull">Tidak
                                                    Diisi</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Liver</label>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="komorbidliverya"
                                                    name="reg_komorbidliver" value="Ya">
                                                <label class="form-check-label" for="komorbidliverya">Ya</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="komorbidliverno"
                                                    name="reg_komorbidliver" value="Tidak">
                                                <label class="form-check-label" for="komorbidliverno">Tidak</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="komorbidlivernull"
                                                    name="reg_komorbidliver" value="Tidak Diisi">
                                                <label class="form-check-label" for="komorbidlivernull">Tidak
                                                    Diisi</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Kronik neurologi / neuromuskula</label>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="komorbidneurologiya"
                                                    name="reg_komorbidneurologi" value="Ya">
                                                <label class="form-check-label" for="komorbidneurologiya">Ya</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="komorbidneurologino"
                                                    name="reg_komorbidneurologi" value="Tidak">
                                                <label class="form-check-label" for="komorbidneurologino">Tidak</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="komorbidneurologinull"
                                                    name="reg_komorbidneurologi" value="Tidak Diisi">
                                                <label class="form-check-label" for="komorbidneurologinull">Tidak
                                                    Diisi</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Imunodefisiensi / HIV</label>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="komorbidhivya"
                                                    name="reg_komorbidhiv" value="Ya">
                                                <label class="form-check-label" for="komorbidhivya">Ya</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="komorbidhivno"
                                                    name="reg_komorbidhiv" value="Tidak">
                                                <label class="form-check-label" for="komorbidhivno">Tidak</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="komorbidhivnull"
                                                    name="reg_komorbidhiv" value="Tidak Diisi">
                                                <label class="form-check-label" for="komorbidhivnull">Tidak
                                                    Diisi</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Penyakit paru kronik</label>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="komorbidparuya"
                                                    name="reg_komorbidparu" value="Ya">
                                                <label class="form-check-label" for="komorbidparuya">Ya</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="komorbidparuno"
                                                    name="reg_komorbidparu" value="Tidak">
                                                <label class="form-check-label" for="komorbidparuno">Tidak</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="komorbidparunull"
                                                    name="reg_komorbidparu" value="Tidak Diisi">
                                                <label class="form-check-label" for="komorbidparunull">Tidak
                                                    Diisi</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <label class="col-md-2">Penyakit ginjal</label>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="komorbidginjalya"
                                                    name="reg_komorbidginjal" value="Ya">
                                                <label class="form-check-label" for="komorbidginjalya">Ya</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="komorbidginjalno"
                                                    name="reg_komorbidginjal" value="Tidak">
                                                <label class="form-check-label" for="komorbidginjalno">Tidak</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="komorbidginjalnull"
                                                    name="reg_komorbidginjal" value="Tidak Diisi">
                                                <label class="form-check-label" for="komorbidginjalnull">Tidak
                                                    Diisi</label>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label" for="gejlain">Keterangan lainnya:
                                            (sebutkan informasi yang dianggap penting)</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" rows="5" name="reg_penjelasanlain"
                                                id="penjelasanlain"></textarea>
                                        </div>
                                    </div>

                                </div>

                                <div id="sw-default-step-6">

                                    <h4 class="mb-1 mt-0">Sampel</h4>
                                    <p><b>Arahkan Kursor ke kotak lalu scan barcode sampel</b></p>
                                    <center>
                                        <input class="form-control col-md-4" type="text"
                                            placeholder="Klik disini lalu scan barcode sampel" id="scanInput" autofocus>
                                    </center>
                                    <div class="field_wrapper mt-3">
                                    </div>

                                    <div id="resultsList"></div>
                                    <div class="form-group row mt-4">
                                        <div class="col-md-12">
                                            <button class="btn btn-md btn-primary" type="submit">Tambahkan
                                                Register</button>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </form>
                </Ibox>
            </div>
        </div>
    </div>
</template>
<script>
    import {
        Form,
        HasError,
        AlertError
    } from 'vform'
    // Vue.component(HasError.name, HasError)
    // Vue.component(AlertError.name, AlertError)
    import axios from 'axios'

    export default {
        middleware: 'auth',
        data: () => ({
            roles: [],
            errors: [],
            form: new Form({
                nomor_register: '',
                email: '',
                username: '',
                role_id: '',
                password: '',
                password_confirmation: ''
            }),
        }),

        created() {
            this.getRoles();
        },

        methods: {
            async getRoles() {
                let resp = await axios.get('/roles-option')
                this.roles = resp.data
            },

            async getNoreg(){
                let resp = await axios.get('/v1/register/noreg')
                this.form.nomor_register = resp.data
            },

            async submitForm() {
                // Tambah User
                this.form.post('/pengguna')
                    .then(({
                        data
                    }) => {
                        console.log('response : ', data);
                        this.$swal.fire(
                            'Berhasil Tambah Data',
                            'Data Pengguna Berhasil ditambahkan',
                            'success'
                        );
                        this.$router.push('/pengguna');
                    })
                    .catch((error) => {
                        console.log(error.response);
                        this.errors = error.response.data.error;
                    });
            }
        },

        mounted() {
            $(document).ready(function () {

                if (document.getElementById('selectktpid').checked) {
                    document.getElementById('sim').style.display = 'none';
                    document.getElementById('ktp').style.display = 'block';
                }
                if (document.getElementById('selectsimid').checked) {
                    document.getElementById('sim').style.display = 'block';
                    document.getElementById('ktp').style.display = 'none';
                }
                if (document.getElementById('pernahrdt').checked) {
                    document.getElementById('ifrdt').style.display = 'block';
                }
                if (document.getElementById('kelperempuan').checked) {
                    document.getElementById('ifcewe').style.display = 'block';
                }
                if (document.getElementById("dinkespengirim").value == "Other") {
                    document.getElementById("inputdaerahlain").style.display = "block";
                }

                if (document.getElementById("rsfasyankes").value == "Other") {
                    document.getElementById("inputrslain").style.display = "block";
                }

                $("#smartwizard-default").smartWizard({
                    useURLhash: !1,
                    showStepURLhash: !1,
                    anchorSettings: {
                        anchorClickable: true, // Enable/Disable anchor navigation
                        enableAllAnchors: true, // Activates all anchors clickable all times
                    },
                    keyNavigation: false, // Enable/Disable keyboard navigation(left and right keys are used if enabled)
                    lang: { // Language variables
                        next: 'Selanjutnya',
                        previous: 'Sebelumnya'
                    }
                });


                $("#tanggalkunjungan").flatpickr({
                    maxDate: new Date(),
                    dateFormat: "Y/m/d",
                    allowInput: true
                });
                $("#tanggalkunjungan_pasien1").flatpickr({
                    maxDate: new Date(),
                    dateFormat: "Y/m/d",
                    allowInput: true
                });
                $("#tanggalkunjungan_pasien2").flatpickr({
                    maxDate: new Date(),
                    dateFormat: "Y/m/d",
                    allowInput: true
                });
                $("#tanggalkunjungan_pasien3").flatpickr({
                    maxDate: new Date(),
                    dateFormat: "Y/m/d",
                    allowInput: true
                });
                $("#tanggalkunjungan_pasien4").flatpickr({
                    maxDate: new Date(),
                    dateFormat: "Y/m/d",
                    allowInput: true
                });
                $("#tanggalkunjungan_pasien5").flatpickr({
                    maxDate: new Date(),
                    dateFormat: "Y/m/d",
                    allowInput: true
                });
                $("#tanggalkunjungan_pasien6").flatpickr({
                    maxDate: new Date(),
                    dateFormat: "Y/m/d",
                    allowInput: true
                });
                $("#tanggalkunjungan_pasien7").flatpickr({
                    maxDate: new Date(),
                    dateFormat: "Y/m/d",
                    allowInput: true
                });
                $("#tanggalkonawal1").flatpickr({
                    maxDate: new Date(),
                    dateFormat: "Y/m/d",
                    allowInput: true
                });
                $("#tanggalkonawal2").flatpickr({
                    maxDate: new Date(),
                    dateFormat: "Y/m/d",
                    allowInput: true
                });
                $("#tanggalkonawal3").flatpickr({
                    maxDate: new Date(),
                    dateFormat: "Y/m/d",
                    allowInput: true
                });
                $("#tanggalkonawal4").flatpickr({
                    maxDate: new Date(),
                    dateFormat: "Y/m/d",
                    allowInput: true
                });
                $("#tanggalkonawal5").flatpickr({
                    maxDate: new Date(),
                    dateFormat: "Y/m/d",
                    allowInput: true
                });
                $("#tanggalkonawal6").flatpickr({
                    maxDate: new Date(),
                    dateFormat: "Y/m/d",
                    allowInput: true
                });
                $("#tanggalkonawal7").flatpickr({
                    maxDate: new Date(),
                    dateFormat: "Y/m/d",
                    allowInput: true
                });
                $("#tanggalkonakhir1").flatpickr({
                    maxDate: new Date(),
                    dateFormat: "Y/m/d",
                    allowInput: true
                });
                $("#tanggalkonakhir2").flatpickr({
                    maxDate: new Date(),
                    dateFormat: "Y/m/d",
                    allowInput: true
                });
                $("#tanggalkonakhir3").flatpickr({
                    maxDate: new Date(),
                    dateFormat: "Y/m/d",
                    allowInput: true
                });
                $("#tanggalkonakhir4").flatpickr({
                    maxDate: new Date(),
                    dateFormat: "Y/m/d",
                    allowInput: true
                });
                $("#tanggalkonakhir5").flatpickr({
                    maxDate: new Date(),
                    dateFormat: "Y/m/d",
                    allowInput: true
                });
                $("#tanggalkonakhir6").flatpickr({
                    maxDate: new Date(),
                    dateFormat: "Y/m/d",
                    allowInput: true
                });
                $("#tanggalkonakhir7").flatpickr({
                    maxDate: new Date(),
                    dateFormat: "Y/m/d",
                    allowInput: true
                });
                $("#tanggallahir").flatpickr({
                    maxDate: new Date(),
                    dateFormat: "Y/m/d",
                    allowInput: true
                });
                $("#tanggalrdt").flatpickr({
                    maxDate: new Date(),
                    dateFormat: "Y/m/d",
                    allowInput: true
                });
                $("#onsetpanas").flatpickr({
                    maxDate: new Date(),
                    dateFormat: "Y/m/d",
                    allowInput: true
                });

                function dinkesotheroptionselect(that) {
                    if (that.value == "Other") {
                        document.getElementById("inputdaerahlain").style.display = "block";
                    } else {
                        document.getElementById("inputdaerahlain").style.display = "none";
                    }
                }

                function rsotheroptionselect(that) {
                    if (that.value == "Other") {
                        document.getElementById("inputrslain").style.display = "block";
                    } else {
                        document.getElementById("inputrslain").style.display = "none";
                    }
                }
            });
        },
    }
</script>