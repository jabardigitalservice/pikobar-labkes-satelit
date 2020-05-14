<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <h2 class="heading-title font-weight-bold text-nowrap mr-3">
                        <i class="uil-eye"></i>
                        Verifikasi
                    </h2>
                </div>
            </div>
            <div class="col-md-6">
                <div class="ibox">
                    <div class="ibox-content">
                        <div>
                            <div>
                                <small class="font-weight-bold">Belum Diverifikasi</small>
                                <h2 class="text-success font-weight-bold">{{unverifyCounter}}</h2>
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
                                <small class="font-weight-bold">Terverifikasi</small>
                                <h2 class="text-warning font-weight-bold">{{verifiedCounter}}</h2>
                                <small class="text-muted">Orang</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <h2 class="heading-title font-weight-bold text-nowrap mr-3">
                        <i class="uil-check"></i>
                        Validasi
                    </h2>
                </div>
            </div>
            <div class="col-md-6">
                <div class="ibox">
                    <div class="ibox-content">
                        <div>
                            <div>
                                <small class="font-weight-bold">Belum Divalidasi</small>
                                <h2 class="text-success font-weight-bold">{{unvalidateCounter}}</h2>
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
                                <h2 class="text-warning font-weight-bold">{{validatedCounter}}</h2>
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
    import axios from 'axios'
    export default {
        name: "data-verifikasi",
        data() {
            return {
                loading: true,
                unverifyCounter: 0,
                verifiedCounter: 0,
                unvalidateCounter: 0,
                validatedCounter: 0,
            };
        },
        methods: {
            async loadData() {
                this.loading = true;
                try {
                    let respUnverify = await axios.get("/v1/dashboard/counter-belum-verifikasi");
                    this.unverifyCounter = respUnverify.data.data;

                    let respVerified = await axios.get("/v1/dashboard/counter-terverifikasi");
                    this.verifiedCounter = respVerified.data.data;

                    let respUnvalidate = await axios.get("/v1/dashboard/counter-belum-validasi");
                    this.unvalidateCounter = respUnvalidate.data.data;

                    let respValidated = await axios.get("/v1/dashboard/counter-tervalidasi");
                    this.validatedCounter = respValidated.data.data;
                } catch (e) {
                    this.unverifyCounter = '-';
                    this.verifiedCounter = '-';
                    this.unvalidateCounter = '-';
                    this.validatedCounter = '-';
                }
                this.loading = false;
            }
        },
        created() {
            this.loadData();
        }
    }
</script>