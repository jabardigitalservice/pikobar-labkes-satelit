<template>
    <div>
        <div class="row">
            <div class="col-md-6">
                <Ibox title="Registrasi Masuk Mandiri">
                    <template v-slot:tools>
                        <select class="form-control h-auto mb-1 p-0 w-auto" v-model="params.mandiri">
                            <option value="Daily">Daily</option>
                            <option value="Monthly">Monthly</option>
                            <!-- <option value="Yearly">Yearly</option> -->
                        </select>
                    </template>
                    <chart-mandiri :barId="'RegistrasiMasukMandiri'"></chart-mandiri>
                    <!-- <chart-bar :barId="'RegistrasiMasukMandiri'" :labels="labels"></chart-bar> -->
                </Ibox>
            </div>
            <div class="col-md-6">
                <Ibox title="Registrasi Masuk Rujukan">
                    <template v-slot:tools>
                        <select class="form-control h-auto mb-1 p-0 w-auto" v-model="params.rujukan">
                            <option value="Daily">Daily</option>
                            <option value="Monthly">Monthly</option>
                            <!-- <option value="Yearly">Yearly</option> -->
                        </select>
                    </template>
                    <!-- <chart-bar :barId="'RegistrasiMasukRujukan'"></chart-bar> -->
                    <chart-rujukan :barId="'RegistrasiMasukRujukan'"></chart-rujukan>
                </Ibox>
            </div>
            <div class="col-md-6">
                <Ibox title="Sampel Ekstraksi">
                    <template v-slot:tools>
                        <select class="form-control h-auto mb-1 p-0 w-auto" v-model="params.ekstraksi">
                            <option value="Daily">Daily</option>
                            <option value="Monthly">Monthly</option>
                            <!-- <option value="Yearly">Yearly</option> -->
                        </select>
                    </template>
                    <!-- <chart-bar :barId="'sampelEkstraksi'"></chart-bar> -->
                    <chart-ekstraksi :barId="'sampelEkstraksi'"></chart-ekstraksi>
                </Ibox>
            </div>
            <div class="col-md-6">
                <Ibox title="Sampel PCR">
                    <template v-slot:tools>
                        <select class="form-control h-auto mb-1 p-0 w-auto" v-model="params.pcr">
                            <option value="Daily">Daily</option>
                            <option value="Monthly">Monthly</option>
                            <!-- <option value="Yearly">Yearly</option> -->
                        </select>
                    </template>
                    <!-- <chart-bar :barId="'samplePCR'"></chart-bar> -->
                    <chart-pcr :barId="'samplePCR'"></chart-pcr>
                </Ibox>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <Ibox title="Hasil Positif">
                    <template v-slot:tools>
                        <select class="form-control h-auto mb-1 p-0 w-auto" v-model="params.positif">
                            <option value="Daily">Daily</option>
                            <option value="Monthly">Monthly</option>
                            <!-- <option value="Yearly">Yearly</option> -->
                        </select>
                    </template>
                    <chart-positif :barId="'HasilPositif'"></chart-positif>
                </Ibox>
            </div>
            <div class="col-md-6">
                <Ibox title="Hasil Negatif">
                    <template v-slot:tools>
                        <select class="form-control h-auto mb-1 p-0 w-auto" v-model="params.negatif">
                            <option value="Daily">Daily</option>
                            <option value="Monthly">Monthly</option>
                            <!-- <option value="Yearly">Yearly</option> -->
                        </select>
                    </template>
                    <chart-negatif :barId="'HasilNegatif'"></chart-negatif>
                </Ibox>
            </div>
        </div>
        <div class="row" v-if="false">
            <div class="col-md-12">

                <Ibox title="Jumlah sampel yang diperiksa menggunakan metode PCR">

                    <template v-slot:tools>
                        <select class="form-control h-auto mb-1 p-0 w-auto">
                            <option value="Daily">Daily</option>
                            <option value="Monthly">Monthly</option>
                            <!-- <option value="Yearly">Yearly</option> -->
                        </select>
                    </template>
                    <chart-stacked-bar />
                </Ibox>
            </div>
        </div>
    </div>
</template>

<script>
    import ChartBar from "../../components/global/chart-bar";
    import ChartPie from "../../components/global/chart-pie";
    import ChartStackedBar from "../../components/global/chart-stacked-bar";
    import ChartMandiri from "./chart/ChartMandiri";
    import ChartRujukan from "./chart/ChartRujukan";
    import ChartPositif from "./chart/ChartPositif";
    import ChartNegatif from "./chart/ChartNegatif";
    import ChartEkstraksi from "./chart/ChartEkstraksi";
    import ChartPcr from "./chart/ChartPcr";
    export default {
        name: "charts",
        components: {
            ChartBar,
            ChartPie,
            ChartStackedBar,
            ChartRujukan,
            ChartMandiri,
            ChartPositif,
            ChartNegatif,
            ChartEkstraksi,
            ChartPcr
        },
        data() {
            return {
                //   loading: true,
                data: {
                    status: {},
                    labs: [],
                    
                },
                params: {
                    mandiri: 'Daily',
                    rujukan: 'Daily',
                    ekstraksi: 'Daily',
                    pcr: 'Daily',
                    positif: 'Daily',
                    negatif: 'Daily'
                }
            };
        },
        watch:{
            "params.mandiri":function(newVal, oldVal){
                this.$bus.$emit('refresh-chart-mandiri', this.params.mandiri)
            },
            "params.rujukan":function(newVal, oldVal){
                this.$bus.$emit('refresh-chart-rujukan', this.params.rujukan)
            },
            "params.pcr":function(newVal, oldVal){
                this.$bus.$emit('refresh-chart-pcr', this.params.pcr)
            },
            "params.ekstraksi":function(newVal, oldVal){
                this.$bus.$emit('refresh-chart-ekstraksi', this.params.ekstraksi)
            },
            "params.positif":function(newVal, oldVal){
                this.$bus.$emit('refresh-chart-positif', this.params.positif)
            },
            "params.negatif":function(newVal, oldVal){
                this.$bus.$emit('refresh-chart-negatif', this.params.negatif)
            } 
        }
    }
</script>