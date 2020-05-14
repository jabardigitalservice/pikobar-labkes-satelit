<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <h2 class="heading-title font-weight-bold text-nowrap mr-3">
                        <i class="uil-atom"></i>
                        rRT-PCR
                    </h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ibox">
                    <div class="ibox-content">
                        <div>
                            <div>
                                <small class="font-weight-bold">Total Sampel Baru</small>
                                <h2 class="text-success font-weight-bold">{{data.status.pcr_sample_received | formatCurrency}}</h2>
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
                                <small class="font-weight-bold">Total Sampel Yang Telah Di PCR </small>
                                <h2 class="text-warning font-weight-bold">{{data.status.sample_valid | formatCurrency}}</h2>
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
                                <small class="font-weight-bold">Total Sampel re-PCR</small>
                                <h2 class="text-navy font-weight-bold">{{data.status.sample_inconclusive | formatCurrency}}</h2>
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
    import axios from 'axios';
    export default {
        name: "data-rrt-pcr",
        data() {
            return {
                loading: true,
                data: {
                    status: {},
                    labs: [],
                }
            };
        },
        methods: {
            async loadData() {
                this.loading = true;
                try {
                    let resp = await axios.get("/v1/dashboard/pcr");
                    this.data = resp.data;
                } catch (e) {
                    this.data.status.extraction_sample_sent = "-";
                    this.data.status.pcr_sample_received = "-";
                    this.data.status.sample_valid = "-";
                    this.data.status.sample_inconclusive = "-";
                }
                this.loading = false;
            }
        },
        created() {
            this.loadData();
        }

    }
</script>