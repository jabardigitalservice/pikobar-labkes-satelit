<template>
    <div class="wrapper wrapper-content">
        <portal to="title-name">Penerimaan dan Ekstraksi RNA Baru</portal>
        <portal to="title-action">
            <div class="title-action">
                <nuxt-link to="/ekstraksi" class="btn btn-primary"><i class="uil-arrow-left"></i>Kembali</nuxt-link>
            </div>
        </portal>

        <div class="row">
            <div class="col-lg-12">
                <Ibox title="No. Ekstraksi : 1">
                    <template v-slot:header>
                        <p class="badge badge-danger">Identitas Pasien Belum Dimasukan Register</p>
                    </template>

                    <p class="badge badge-info">Pilih sampel untuk dikirimkan ke bagian pemeriksaan sampel</p>
                    <form method="" action="">
                        <div class="form-check" v-for="(item,idx) in sample" :key="idx">
                            <input class="form-check-input" type="radio" name="sampel_id"
                                :id="`radiosample-${item.pen_id}`" :value="item.pen_id" checked>
                            <label class="form-check-label" :for="`radiosample-${item.pen_id}`">
                                <h2 style="margin-top:-3px"> Sampel No #{{item.pen_id}} <button type="button"
                                        class="btn btn-info btn-sm"><i class="fa fa-search"></i></button></h2>
                                <h4>Whole Blod</h4>
                            </label>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-2">Tanggal penerimaan sampel <span style="color:red">*</span></label>
                            <div class="col-md-6">
                                <input class="form-control flatpickr-input" type="text" id="tglpenerimaansampel"
                                    name="eks_tanggal_penerimaan_sampel" placeholder="YYYY/MM/DD">
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-2">Jam penerimaan sampel <span style="color:red">*</span></label>
                            <div class="col-md-6">
                                <input class="form-control" type="text" name="eks_jam_penerimaan_sampel"
                                    placeholder="JJ:MM">
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-2">Petugas penerima sampel <span style="color:red">*</span></label>
                            <div class="col-md-6">
                                <input class="form-control" type="text" name="eks_petugas_penerima_sampel"
                                    placeholder="Petugas penerima sampel">
                            </div>
                        </div>


                        <div class="form-group row mt-4">
                            <label class="col-md-2">Petugas penerima sampel <span style="color:red">*</span></label>
                            <div class="col-md-6">
                                <input class="form-control" type="text" name="eks_petugas_penerima_sampel"
                                    placeholder="Petugas penerima sampel">
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-2">Operator ekstraksi <span style="color:red">*</span></label>
                            <div class="col-md-6">
                                <input class="form-control" type="text" name="eks_operator_ekstraksi"
                                    placeholder="Operator ekstraksi">
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-2">Tanggal mulai ekstraksi <span style="color:red">*</span></label>
                            <div class="col-md-6">
                                <input class="form-control flatpickr-input" type="text" id="tglmulaiekstraksi"
                                    name="eks_tanggal_mulai_ekstraksi" placeholder="YYYY/MM/DD">
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-2">Jam mulai ekstraksi <span style="color:red">*</span></label>
                            <div class="col-md-6">
                                <input class="form-control" type="text" name="eks_jam_mulai_ekstraksi"
                                    placeholder="JJ:MM">
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-2">Metode ekstraksi <span style="color:red">*</span></label>
                            <div class="col-md-6">
                                <input class="form-control" type="text" name="eks_metode_ekstraksi"
                                    placeholder="Metode ekstraksi">
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-2">Nama kit ekstraksi <span style="color:red">*</span></label>
                            <div class="col-md-6">
                                <input class="form-control" type="text" name="eks_nama_kit_ekstraksi"
                                    placeholder="Nama kit ekstraksi">
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-2">Dikirim ke Lab <span style="color:red">*</span></label>
                            <div class="col-md-6">
                                <div class="form-group" style="margin-left:20px">
                                    <input class="form-check-input" type="radio" name="eks_dikirim_ke_lab" id="labunpad"
                                        value="Unpad"><label for="labunpad">Unpad</label>
                                    <br>
                                    <input class="form-check-input" type="radio" name="eks_dikirim_ke_lab"
                                        id="lablabkes" value="LABKES"><label for="lablabkes">LABKES</label>
                                </div>
                                <label>Lainnya, sebutkan nama lab <small>kosongkan jika terdapat
                                        pilihannya</small></label>
                                <input class="form-control" type="text" name="eks_nama_lab_lain">
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-2">Nama pengirim RNA <span style="color:red">*</span></label>
                            <div class="col-md-6">
                                <input class="form-control" type="text" name="eks_nama_pengirim_rna"
                                    placeholder="Nama pengirim RNA">
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-2">Tanggal pengiriman RNA <span style="color:red">*</span></label>
                            <div class="col-md-6">
                                <input class="form-control flatpickr-input" type="text" id="tanggalpengirimanrna"
                                    name="eks_tanggal_pengiriman_rna" placeholder="YYYY/MM/DD">
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <label class="col-md-2">Jam pengiriman RNA <span style="color:red">*</span></label>
                            <div class="col-md-6">
                                <input class="form-control" type="text" name="eks_jam_pengiriman_rna"
                                    placeholder="JJ:MM">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label" for="gejlain">Catatan Lain</label>
                            <div class="col-md-10">
                                <textarea class="form-control" rows="5" name="eks_catatan"
                                    placeholder="Catatan"></textarea>
                            </div>
                        </div>

                        <div class="form-group row mt-4">
                            <div class="col-md-12">
                                <button class="btn btn-md btn-primary" type="submit">Pilih dan Kirim Sampel</button>
                            </div>
                        </div>
                    </form>

                </Ibox>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        middleware: "auth",
        data() {
            return {
                params1: {
                    sam_barcodenomor_sampel: null
                },
                params2: {
                    sam_barcodenomor_sampel: null
                },
                sample: [{
                    'pen_id': '111',
                    'petugas': 'Andri',
                    'tanggal_sampel': '2020-04-24',
                    'jam_sampel': '14:00',
                    'jenis': 'Whole Blood',
                }]
            };
        },
        head() {
            return {
                title: "Penerimaan Sampel"
            };
        }
    };
</script>