<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="align-items-center d-flex mb-3">
                    <h2 class="font-weight-bold w-25 m-0 text-nowrap">
                        <i class="fa fa-check"></i>
                        Validasi
                    </h2>
                    <div class="w-100 ml-3">
                        <hr>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="ibox">
                    <div class="ibox-content">
                        <div>
                            <div>
                                <small class="font-weight-bold">Belum Divalidasi</small>
                                <h2 class="text-success font-weight-bold">220</h2>
                                <small class="text-muted">Orang</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="ibox">
                    <div class="ibox-content">
                        <div>
                            <div>
                                <small class="font-weight-bold">Tervalidasi</small>
                                <h2 class="text-warning font-weight-bold">220</h2>
                                <small class="text-muted">Orang</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "data-verifikasi",
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