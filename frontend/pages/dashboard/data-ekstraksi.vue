<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="align-items-center d-flex mb-3">
                    <h2 class="font-weight-bold w-25 m-0 text-nowrap">
                        <i class="fa fa-users"></i>
                        Ekstraksi
                    </h2>
                    <div class="w-100 ml-3">
                        <hr>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ibox">
                    <div class="ibox-content">
                        <div>
                            <div>
                                <small class="font-weight-bold">Total Sampel Baru</small>
                                <h2 class="text-success font-weight-bold">220</h2>
                                <small class="text-muted">Pcs</small>
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
                                <small class="font-weight-bold">Total Sampel Sudah Diekstraksi</small>
                                <h2 class="text-warning font-weight-bold">220</h2>
                                <small class="text-muted">Pcs</small>
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
                                <small class="font-weight-bold">Total Sampel Sudah Dikirim</small>
                                <h2 class="text-navy font-weight-bold">220</h2>
                                <small class="text-muted">Pcs</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <Ibox title="Sampel Dikirim">
                    <div>
                        <div>
                            <small class="font-weight-bold">PCR Labkes </small>
                            <h2 class="text-success font-weight-bold">220</h2>
                            <small class="text-muted">Pcs</small>
                        </div>
                        <hr>
                        <div>
                            <small class="font-weight-bold">Unpad</small>
                            <h2 class="text-warning font-weight-bold">220</h2>
                            <small class="text-muted">Pcs</small>
                        </div>
                        <hr>
                        <div>
                            <small class="font-weight-bold">Rumah Sakit Hasan Sadikin</small>
                            <h2 class="text-navy font-weight-bold">220</h2>
                            <small class="text-muted">Pcs</small>
                        </div>
                    </div>
                </Ibox>
            </div>
            <div class="col-md-6">
                <Ibox title="Sampel Invalid">
                    <div>
                        <div>
                            <small class="font-weight-bold">Total Invalid </small>
                            <h2 class="text-success font-weight-bold">220</h2>
                            <small class="text-muted">Pcs</small>
                        </div>
                        <hr>
                        <div>
                            <small class="font-weight-bold">Sampel Kurang</small>
                            <h2 class="text-warning font-weight-bold">220</h2>
                            <small class="text-muted">Pcs</small>
                        </div>
                        <hr>
                        <div>
                            <small class="font-weight-bold">Sampel Swab Ulang</small>
                            <h2 class="text-navy font-weight-bold">220</h2>
                            <small class="text-muted">Pcs</small>
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