<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <h2 class="heading-title font-weight-bold text-nowrap mr-3">
                        <i class="uil-flask"></i>
                        Ekstraksi
                    </h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ibox">
                    <div class="ibox-content">
                        <div>
                            <div>
                                <small class="font-weight-bold">Total Sampel Baru</small>
                                <h2 class="text-success font-weight-bold">{{data.status.sample_taken?data.status.sample_taken:0}}</h2>
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
                                <h2 class="text-warning font-weight-bold">{{data.status.extraction_sample_extracted?data.status.extraction_sample_extracted:0}}</h2>
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
                                <h2 class="text-navy font-weight-bold">{{data.status.extraction_sent}}</h2>
                                <small class="text-muted">Pcs</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <Ibox title="Sampel Dikirim">
                    <div>
                        <div v-for="(item,idx) in data.send" :key="idx">
                            <div>
                                <small class="font-weight-bold">{{item.nama}} </small>
                                <h2 class="text-success font-weight-bold">{{item.total}}</h2>
                                <small class="text-muted">Pcs</small>
                            </div>
                            <hr v-if="idx+1!=data.send.length">
                        </div>
                    </div>
                </Ibox>
            </div>
            <div class="col-md-6">
                <Ibox title="Sampel Invalid">
                    <div>
                        <div>
                            <small class="font-weight-bold">Total Invalid </small>
                            <h2 class="text-success font-weight-bold">{{data.invalid?data.invalid:0}}</h2>
                            <small class="text-muted">Pcs</small>
                        </div>
                        <hr>
                        <div>
                            <small class="font-weight-bold">Sampel Kurang</small>
                            <h2 class="text-warning font-weight-bold">{{data.kurang?data.kurang:0}}</h2>
                            <small class="text-muted">Pcs</small>
                        </div>
                        <hr>
                        <div>
                            <small class="font-weight-bold">Sampel Swab Ulang</small>
                            <h2 class="text-navy font-weight-bold">{{data.ulang?data.ulang:0}}</h2>
                            <small class="text-muted">Pcs</small>
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
        data() {
            return {
                loading: true,
                data: {
                    status: {},
                    labs: [],
                    send:[],
                }
            };
        },
        methods: {
            async loadData() {
                this.loading = true;
                try {
                    let resp = await axios.get("/v1/dashboard/ekstraksi");
                    this.data = resp.data;
                } catch (e) {
                    this.data.status.sample_taken = "-";
                    this.data.status.extraction_sample_extracted = "-";
                    this.data.status.extraction_sent = "-";
                    this.data.status.extraction_sample_reextract = "-";
                }
                this.loading = false;
            }
        },
        created() {
            this.loadData();
        }
    }
</script>