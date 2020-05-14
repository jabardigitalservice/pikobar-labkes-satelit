<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <h2 class="heading-title font-weight-bold text-nowrap mr-3">
                        <i class="uil-user-plus"></i>
                        Registrasi
                    </h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ibox">
                    <div class="ibox-content">
                        <div>
                            <div>
                                <small class="font-weight-bold">Total Pasien Teregsitrasi</small>
                                <h2 class="text-success font-weight-bold">{{data.total_registrasi?data.total_registrasi:0}}</h2>
                                <small class="text-muted">Orang</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ibox">
                    <div class="ibox-content">
                        <div>
                            <div>
                                <small class="font-weight-bold">Total Pasien Registrasi Mandiri</small>
                                <h2 class="text-warning font-weight-bold">{{data.registrasi_mandiri?data.registrasi_mandiri:0}}</h2>
                                <small class="text-muted">Orang</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ibox">
                    <div class="ibox-content">
                        <div>
                            <div>
                                <small class="font-weight-bold">Total Pasien Registrasi Rujukan</small>
                                <h2 class="text-navy font-weight-bold">{{data.registrasi_rujukan?data.registrasi_rujukan:0}}</h2>
                                <small class="text-muted">Orang</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <Ibox title="Registrasi Mandiri">
                    <div>
                        <div>
                            <small class="font-weight-bold">Jumlah Registrasi Pasien Hari Ini </small>
                            <h2 class="text-success font-weight-bold">{{data.mandiri.today?data.mandiri.today:0}}</h2>
                            <small class="text-muted">Orang</small>
                        </div>
                        <hr>
                        <div>
                            <small class="font-weight-bold">Jumlah Data Belum Lengkap</small>
                            <h2 class="text-warning font-weight-bold">{{data.mandiri.belum_lengkap?data.mandiri.belum_lengkap:0}}</h2>
                            <small class="text-muted">Orang</small>
                        </div>
                        <hr>
                        <div>
                            <small class="font-weight-bold">Jumlah Pemeriksaan Selesai</small>
                            <h2 class="text-navy font-weight-bold">{{data.mandiri.today?data.mandiri.today:0}}</h2>
                            <small class="text-muted">Orang</small>
                        </div>
                    </div>
                </Ibox>
            </div>
            <div class="col-md-6">
                <Ibox title="Registrasi Rujukan">
                    <div>
                        <div>
                            <small class="font-weight-bold">Jumlah Registrasi Pasien Hari Ini </small>
                            <h2 class="text-success font-weight-bold">{{data.rujukan.today?data.rujukan.today:0}}</h2>
                            <small class="text-muted">Orang</small>
                        </div>
                        <hr>
                        <div>
                            <small class="font-weight-bold">Jumlah Data Belum Lengkap</small>
                            <h2 class="text-warning font-weight-bold">{{data.rujukan.belum_lengkap?data.rujukan.belum_lengkap:0}}</h2>
                            <small class="text-muted">Orang</small>
                        </div>
                        <hr>
                        <div>
                            <small class="font-weight-bold">Jumlah Pemeriksaan Selesai</small>
                            <h2 class="text-navy font-weight-bold">{{data.rujukan.done?data.rujukan.done:0}}</h2>
                            <small class="text-muted">Orang</small>
                        </div>
                        <hr>
                        <div>
                            <small class="font-weight-bold">Jumlah Data Belum di Input</small>
                            <h2 class="text-danger font-weight-bold">{{data.rujukan.none?data.rujukan.none:0}}</h2>
                            <small class="text-muted">Sampel</small>
                        </div>
                    </div>
                </Ibox>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    export default {
        name: "data-positif-negatif",
        // async asyncData({
        //     route,
        //     store
        // }) {
        //     let error = false;
        //     let resp = await axios.get("/v1/dashboard/registrasi");
        //     return {
        //         data: resp.data
        //     }
        // },
        data() {
            return {
                data:{
                    total_registrasi:0,
                    registrasi_mandiri:0,
                    registrasi_rujukan:0,
                    mandiri:{
                        today:0,
                        belum_lengkap:0,
                        done:0
                    },
                    rujukan:{
                        today:0,
                        belum_lengkap:0,
                        done:0,
                        none:0
                    }
                }
            }
        },
        methods: {
            async loadData() {
                this.loading = true;
                try {
                    let resp = await axios.get("/v1/dashboard/registrasi");
                    this.data = resp.data;
                    console.log(this.data);
                } catch (e) {
                    console.log('Error : ',e)
                    // this.data.total_registrasi = "-";
                    // this.data.registrasi_mandiri = "-";
                    // this.data.belum_lengkap = "-";
                    // this.data.done = "-";
                }
                this.loading = false;
            }
        },
        created() {
            this.loadData();
        }
    }
</script>