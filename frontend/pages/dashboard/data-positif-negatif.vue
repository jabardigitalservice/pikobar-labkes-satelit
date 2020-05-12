<template>
    <div class="row">
        <div class="col-md-6">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="d-flex">
                        <div class="mr-3">
                            <img src="@/assets/img/positif.jpg" style="width:87px" alt="">
                        </div>
                        <div>
                            <small class="font-weight-bold">Positif</small>
                            <h2 class="text-danger font-weight-bold">220</h2>
                            <small class="text-muted">Orang</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="d-flex">
                        <div class="mr-3">
                            <img src="@/assets/img/negatif.jpg" style="width:87px" alt="">
                        </div>
                        <div>
                            <small class="font-weight-bold">Negatif</small>
                            <h2 class="text-success font-weight-bold">220</h2>
                            <small class="text-muted">Orang</small>
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