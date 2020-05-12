<template>
    <div>
        <div class="row">
            <div class="col-md-6">
                <Ibox title="Registrasi Masuk Mandiri">
                    <template v-slot:tools >
                    <div class="d-sm-block d-none">
                        <select class="form-control h-auto mb-1 p-0 w-auto">
                            <option value="">Daily</option>
                            <option value="">Monthly</option>
                            <option value="">Yearly</option>
                        </select>
                    </div>
                    </template>
                    <div class="d-sm-none mb-2">
                        <select class="form-control h-auto mb-1 p-0 w-auto">
                            <option value="">Daily</option>
                            <option value="">Monthly</option>
                            <option value="">Yearly</option>
                        </select>
                    </div>
                    <chart-bar :barId="'RegistrasiMasukMandiri'"></chart-bar>
                </Ibox>
            </div>
            <div class="col-md-6">
                <Ibox title="Registrasi Masuk Rujukan">
                    <template v-slot:tools >
                    <div class="d-sm-block d-none">
                        <select class="form-control h-auto mb-1 p-0 w-auto">
                            <option value="">Daily</option>
                            <option value="">Monthly</option>
                            <option value="">Yearly</option>
                        </select>
                    </div>
                    </template>
                    <div class="d-sm-none mb-2">
                        <select class="form-control h-auto mb-1 p-0 w-auto">
                            <option value="">Daily</option>
                            <option value="">Monthly</option>
                            <option value="">Yearly</option>
                        </select>
                    </div>
                    <chart-bar :barId="'RegistrasiMasukRujukan'"></chart-bar>
                </Ibox>
            </div>
            <div class="col-md-6">
                <Ibox title="Sampel Ekstraksi">
                    <template v-slot:tools >
                    <div class="d-sm-block d-none">
                        <select class="form-control h-auto mb-1 p-0 w-auto">
                            <option value="">Daily</option>
                            <option value="">Monthly</option>
                            <option value="">Yearly</option>
                        </select>
                    </div>
                    </template>
                    <div class="d-sm-none mb-2">
                        <select class="form-control h-auto mb-1 p-0 w-auto">
                            <option value="">Daily</option>
                            <option value="">Monthly</option>
                            <option value="">Yearly</option>
                        </select>
                    </div>
                    <chart-bar :barId="'sampelEkstraksi'"></chart-bar>
                </Ibox>
            </div>
            <div class="col-md-6">
                <Ibox title="Sampel PCR">
                    <template v-slot:tools >
                    <div class="d-sm-block d-none">
                        <select class="form-control h-auto mb-1 p-0 w-auto">
                            <option value="">Daily</option>
                            <option value="">Monthly</option>
                            <option value="">Yearly</option>
                        </select>
                    </div>
                    </template>
                    <div class="d-sm-none mb-2">
                        <select class="form-control h-auto mb-1 p-0 w-auto">
                            <option value="">Daily</option>
                            <option value="">Monthly</option>
                            <option value="">Yearly</option>
                        </select>
                    </div>
                    <chart-bar :barId="'samplePCR'"></chart-bar>
                </Ibox>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <Ibox title="Hasil Positif">
                    <template v-slot:tools >
                    <div class="d-sm-block d-none">
                        <select class="form-control h-auto mb-1 p-0 w-auto">
                            <option value="">Daily</option>
                            <option value="">Monthly</option>
                            <option value="">Yearly</option>
                        </select>
                    </div>
                    </template>
                    <div class="d-sm-none mb-2">
                        <select class="form-control h-auto mb-1 p-0 w-auto">
                            <option value="">Daily</option>
                            <option value="">Monthly</option>
                            <option value="">Yearly</option>
                        </select>
                    </div>
                    <chart-doughnut></chart-doughnut>
                </Ibox>
            </div>
            <div class="col-md-4">
                <Ibox title="Hasil Negatif">
                    <template v-slot:tools >
                    <div class="d-sm-block d-none">
                        <select class="form-control h-auto mb-1 p-0 w-auto">
                            <option value="">Daily</option>
                            <option value="">Monthly</option>
                            <option value="">Yearly</option>
                        </select>
                    </div>
                    </template>
                    <div class="d-sm-none mb-2">
                        <select class="form-control h-auto mb-1 p-0 w-auto">
                            <option value="">Daily</option>
                            <option value="">Monthly</option>
                            <option value="">Yearly</option>
                        </select>
                    </div>
                    <chart-doughnut></chart-doughnut>
                </Ibox>
            </div>
            <div class="col-md-4">
                <Ibox title="Belum Keluar Hasil">
                    <template v-slot:tools >
                    <div class="d-sm-block d-none">
                        <select class="form-control h-auto mb-1 p-0 w-auto">
                            <option value="">Daily</option>
                            <option value="">Monthly</option>
                            <option value="">Yearly</option>
                        </select>
                    </div>
                    </template>
                    <div class="d-sm-none mb-2">
                        <select class="form-control h-auto mb-1 p-0 w-auto">
                            <option value="">Daily</option>
                            <option value="">Monthly</option>
                            <option value="">Yearly</option>
                        </select>
                    </div>
                    <chart-doughnut></chart-doughnut>
                </Ibox>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                
            </div>
        </div>
    </div>
</template>

<script>
import ChartBar from "../../components/global/chart-bar";
import ChartDoughnut from "../../components/global/chart-doughnut";

export default {
    name: "charts",
    components: {
        ChartBar,
        ChartDoughnut
    },
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