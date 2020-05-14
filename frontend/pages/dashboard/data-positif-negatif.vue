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
                            <h2 class="text-danger font-weight-bold">{{positif | formatCurrency}}</h2>
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
                            <h2 class="text-success font-weight-bold">{{negatif | formatCurrency}}</h2>
                            <small class="text-muted">Orang</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    export default {
        name: "data-positif-negatif",
        data() {
            return {
                //   loading: true,
                negatif:0,
                positif:0,
            };
        },
        methods: {
            async loadData() {
                this.loading = true;
                try {
                    let resp = await axios.get("/v1/dashboard/positif-negatif");
                    this.negatif = resp.data.negatif;
                    this.positif = resp.data.positif
                } catch (e) {
                   console.log(e);
                }
                this.loading = false;
            },
        },
        created() {
            this.loadData();
        }
    }
</script>