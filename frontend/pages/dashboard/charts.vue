<template>
    <div>
        <div class="row">
            <div class="col-md-12">
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
    </div>
</template>

<script>
    import ChartPcr from "./chart/ChartPcr";
    export default {
        name: "charts",
        components: {
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
                    pcr: 'Daily',
                }
            };
        },
        watch:{
            "params.pcr":function(newVal, oldVal){
                this.$bus.$emit('refresh-chart-pcr', this.params.pcr)
            },
        }
    }
</script>