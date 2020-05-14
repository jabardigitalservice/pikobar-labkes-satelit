<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <h2 class="heading-title font-weight-bold text-nowrap mr-3">
                        <i class="uil-flask-potion"></i>
                        Pengambilan Sampel
                    </h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ibox">
                    <div class="ibox-content">
                        <div>
                            <div>
                                <small class="font-weight-bold">Total Sampel</small>
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
                                <small class="font-weight-bold">Total Sampel Masuk Hari Ini</small>
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
                                <small class="font-weight-bold">Total Sampel Dari Registrasi Mandiri</small>
                                <h2 class="text-navy font-weight-bold">220</h2>
                                <small class="text-muted">Pcs</small>
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