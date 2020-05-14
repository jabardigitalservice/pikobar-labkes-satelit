<template>
    <div>
        <div class="row">
            <div class="col-md-6">
                <Ibox title="Registrasi Masuk Mandiri">
                    <template v-slot:tools >
                        <select class="form-control h-auto mb-1 p-0 w-auto">
                            <option value="">Daily</option>
                            <option value="">Monthly</option>
                            <option value="">Yearly</option>
                        </select>
                    </template>
                    <chart-mandiri :barId="'RegistrasiMasukMandiri'"></chart-mandiri>
                    <!-- <chart-bar :barId="'RegistrasiMasukMandiri'" :labels="labels"></chart-bar> -->
                </Ibox>
            </div>
            <div class="col-md-6">
                <Ibox title="Registrasi Masuk Rujukan">
                    <template v-slot:tools >
                        <select class="form-control h-auto mb-1 p-0 w-auto">
                            <option value="">Daily</option>
                            <option value="">Monthly</option>
                            <option value="">Yearly</option>
                        </select>
                    </template>
                    <!-- <chart-bar :barId="'RegistrasiMasukRujukan'"></chart-bar> -->
                    <chart-rujukan :barId="'RegistrasiMasukRujukan'"></chart-rujukan>
                </Ibox>
            </div>
            <div class="col-md-6">
                <Ibox title="Sampel Ekstraksi">
                    <template v-slot:tools >
                        <select class="form-control h-auto mb-1 p-0 w-auto">
                            <option value="">Daily</option>
                            <option value="">Monthly</option>
                            <option value="">Yearly</option>
                        </select>
                    </template>
                    <chart-bar :barId="'sampelEkstraksi'"></chart-bar>
                </Ibox>
            </div>
            <div class="col-md-6">
                <Ibox title="Sampel PCR">
                    <template v-slot:tools >
                        <select class="form-control h-auto mb-1 p-0 w-auto">
                            <option value="">Daily</option>
                            <option value="">Monthly</option>
                            <option value="">Yearly</option>
                        </select>
                    </template>
                    <chart-bar :barId="'samplePCR'"></chart-bar>
                </Ibox>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <Ibox title="Hasil Positif">
                    
                    <template v-slot:tools >
                        <select class="form-control h-auto mb-1 p-0 w-auto">
                            <option value="">Daily</option>
                            <option value="">Monthly</option>
                            <option value="">Yearly</option>
                        </select>
                    </template>
                    <chart-pie></chart-pie>
                </Ibox>
            </div>
            <div class="col-md-4">
                <Ibox title="Hasil Negatif">
                    
                    <template v-slot:tools >
                        <select class="form-control h-auto mb-1 p-0 w-auto">
                            <option value="">Daily</option>
                            <option value="">Monthly</option>
                            <option value="">Yearly</option>
                        </select>
                    </template>
                    <chart-pie></chart-pie>
                </Ibox>
            </div>
            <div class="col-md-4">
                <Ibox title="Belum Keluar Hasil">
                    
                    <template v-slot:tools >
                        <select class="form-control h-auto mb-1 p-0 w-auto">
                            <option value="">Daily</option>
                            <option value="">Monthly</option>
                            <option value="">Yearly</option>
                        </select>
                    </template>
                    <chart-pie></chart-pie>
                </Ibox>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                
                <Ibox title="Jumlah sampel yang diperiksa menggunakan metode PCR">
                    
                    <template v-slot:tools >
                        <select class="form-control h-auto mb-1 p-0 w-auto">
                            <option value="">Daily</option>
                            <option value="">Monthly</option>
                            <option value="">Yearly</option>
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
export default {
    name: "charts",
    components: {
        ChartBar,
        ChartPie,
        ChartStackedBar,
        ChartRujukan,
        ChartMandiri
    },
    data() {
        return {
        //   loading: true,
        data: {
            status: {},
            labs: [],
        },
        };
    },
}
</script>