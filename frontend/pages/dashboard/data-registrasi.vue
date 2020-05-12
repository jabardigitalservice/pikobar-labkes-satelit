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
                                <h2 class="text-success font-weight-bold">220</h2>
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
                                <h2 class="text-warning font-weight-bold">220</h2>
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
                                <h2 class="text-navy font-weight-bold">220</h2>
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
                            <h2 class="text-success font-weight-bold">220</h2>
                            <small class="text-muted">Orang</small>
                        </div>
                        <hr>
                        <div>
                            <small class="font-weight-bold">Jumlah Data Belum Lengkap</small>
                            <h2 class="text-warning font-weight-bold">220</h2>
                            <small class="text-muted">Orang</small>
                        </div>
                        <hr>
                        <div>
                            <small class="font-weight-bold">Jumlah Pemeriksaan Selesai</small>
                            <h2 class="text-navy font-weight-bold">220</h2>
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
                            <h2 class="text-success font-weight-bold">220</h2>
                            <small class="text-muted">Orang</small>
                        </div>
                        <hr>
                        <div>
                            <small class="font-weight-bold">Jumlah Data Belum Lengkap</small>
                            <h2 class="text-warning font-weight-bold">220</h2>
                            <small class="text-muted">Orang</small>
                        </div>
                        <hr>
                        <div>
                            <small class="font-weight-bold">Jumlah Pemeriksaan Selesai</small>
                            <h2 class="text-navy font-weight-bold">220</h2>
                            <small class="text-muted">Orang</small>
                        </div>
                        <hr>
                        <div>
                            <small class="font-weight-bold">Jumlah Data Belum di Input</small>
                            <h2 class="text-danger font-weight-bold">220</h2>
                            <small class="text-muted">Orang</small>
                        </div>
                    </div>
                </Ibox>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "data-positif-negatif",
  data() {
    return {
    //   loading: true,
      data: {
        status: {},
        labs: [],
      }
    };
  },
}
</script>