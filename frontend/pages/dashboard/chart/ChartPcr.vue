<template>
  <div v-if="start">
    <canvas :id="barId" count="1" height="100px" />
    <chartjs-bar v-for="(item, index) in types" :key="index" :backgroundcolor="item.bgColor" :beginzero="beginZero"
      :bind="true" :bordercolor="item.borderColor" :data="item.data" :datalabel="item.dataLabel" :labels="labels"
      :target="barId" :options="options" />
  </div>
</template>

<script>
  import axios from 'axios'
  export default {
    props: ['barId'],
    name: 'ChartMandiri',
    data() {
      return {
        start: false,
        beginZero: true,
        labels: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
        types: [{
          bgColor: "#de98ab",
          borderColor: "0c0306",
          data: [],
          dataLabel: "Positif"
        }, ],
        options: {
          title: {
            display: true,
            text: 'Chart.js Bar Chart - Stacked'
          },
          tooltips: {
            mode: 'index',
            intersect: false
          },
          responsive: true,
          scales: {
            xAxes: [{
              stacked: true,
            }],
            yAxes: [{
              stacked: true
            }]
          }
        }
      }
    },
    methods: {
      async loadData(tipe) {
        let resp = await axios.get('v1/chart/pcr?tipe=' + tipe)
        this.labels = resp.data.label;
        this.types = resp.data.data
      }
    },
    created() {
      this.start = false;
      setTimeout(() => {
        this.loadData('Daily');
        this.start = true;
      }, 1000);

    },
    mounted() {
      this.$bus.$on('refresh-chart-pcr', (tipe) => {
        // alert(tipe)
        this.loadData(tipe)
      })
    }
  }; 
  </script>