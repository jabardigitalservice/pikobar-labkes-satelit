<template>
    <div class="wrapper wrapper-content">
        <portal to="title-name">Detail Status</portal>
        <portal to="title-action">
            <div class="title-action">
                <router-link to="/sample/add" class="btn btn-warning">
                    <i class="fa fa-edit"></i> Ubah
                </router-link>
            </div>
        </portal>

        <div class="row">
            <div class="col-lg-12">
                <Ibox title="Detail Ekstraksi & Pengiriman Sampel">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="header-title mt-2 mb-2">Informasi Ekstraksi &amp; Pengiriman Sampel</h3>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td width="40%"><b>Nomor Registrasi</b></td>
                                        <td width="60%"> 
                                            <span class="badge badge-danger" v-if="data.eks_noreg==null">Identitas Pasien Belum
                                                Dimasukan Register</span>
                                            <span v-else>{{data.eks_noreg}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="40%"><b>Nomor Induk Kependudukan</b></td>
                                        <td width="60%"> 
                                            <span class="badge badge-danger" v-if="data.eks_nik==null">Identitas Pasien Belum
                                                Dimasukan Register</span>
                                            <span v-else>{{data.eks_nik}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="40%"><b>Sampel yang Dikirim</b></td>
                                        <td width="60%"><span class="badge badge-primary">{{data.sam_barcodenomor_sampel}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="40%"><b>Tanggal penerimaan sampel</b></td>
                                        <td width="60%">{{data.eks_tanggal_penerimaan_sampel}} pada {{data.eks_jam_penerimaan_sampel}}</td>
                                    </tr>
                                    <tr>
                                        <td width="40%"><b>Petugas Penerima Sampel</b></td>
                                        <td width="60%">{{data.eks_petugas_penerima_sampel}}</td>
                                    </tr>
                                    <tr>
                                        <td width="40%"><b>Tanggal Mulai Ekstraksi</b></td>
                                        <td width="60%">{{data.eks_tanggal_mulai_ekstraksi }} pada {{data.eks_jam_mulai_ekstraksi}}</td>
                                    </tr>
                                    <tr>
                                        <td width="40%"><b>Metode Ekstraksi</b></td>
                                        <td width="60%">{{data.eks_metode_ekstraksi}}</td>
                                    </tr>
                                    <tr>
                                        <td width="40%"><b>Nama Kit Ekstraksi</b></td>
                                        <td width="60%">{{data.eks_nama_kit_ekstraksi}}</td>
                                    </tr>
                                    <tr>
                                        <td width="40%"><b>Dikirim ke Lab</b></td>
                                        <td width="60%">
                                            {{data.eks_dikirim_ke_lab!=null?data.eks_dikirim_ke_lab:data.eks_nama_lab_lain}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="40%"><b>Nama Pengirim RNA</b></td>
                                        <td width="60%">{{data.eks_nama_pengirim_rna }}</td>
                                    </tr>
                                    <tr>
                                        <td width="40%"><b>Tanggal Pengiriman RNA</b></td>
                                        <td width="60%">{{data.eks_tanggal_pengiriman_rna }} pada {{data.eks_jam_pengiriman_rna }}</td>
                                    </tr>
                                    <tr>
                                        <td width="40%"><b>Catatan</b></td>
                                        <td width="60%">{{data.eks_catatan}}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <hr>
                            <h3 class="header-title mt-2 mb-2">Riwayat Perubahan atau Pengiriman Kembali</h3>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="20%">Tanggal Perubahan</th>
                                    <th>Keterangan</th>
                                </tr>
                                <tr v-for="(item,idx) in perubahan" :key="idx">
                                    <td>{{item.tanggal_perubahan}}</td>
                                    <td>{{item.keterangan}}</td>
                                </tr>
                            </table>

                        </div>
                    </div>
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
                perubahan:[
                    {
                        tanggal_perubahan: '2020-04-23',
                        keterangan: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Odio illo repellat maxime, consequatur perspiciatis ipsum, iure sequi exercitationem aut reprehenderit illum beatae aspernatur, enim nesciunt officia tempore facilis perferendis. Molestias!'
                    }
                ],
                data:{
                    eks_noreg:null,
                    eks_nik : null,
                    sam_barcodenomor_sampel: 1,
                    eks_tanggal_penerimaan_sampel: '2020-04-23',
                    eks_jam_penerimaan_sampel: '14:00',
                    eks_petugas_penerima_sampel: 'Andri',
                    eks_tanggal_mulai_ekstraksi: '2020-04-24',
                    eks_jam_mulai_ekstraksi: '14:00',
                    eks_metode_ekstraksi: 'Diputar, Dijilat, Dicelupin',
                    eks_nama_kit_ekstraksi: 'Oreo Double Protection',
                    eks_dikirim_ke_lab: null,
                    eks_nama_lab_lain: 'Uniliver',
                    eks_nama_pengirim_rna: 'Ani',
                    eks_tanggal_pengiriman_rna: '2020-04-20',
                    eks_jam_pengiriman_rna: '10:00',
                    eks_catatan:'Tidak ada cataan'
                }
            };
        },
        head() {
            return {
                title: "Penerimaan Sampel"
            };
        }
    };
</script>