<template>
  <div>
    <canvas height="100px" :chart="chart" id="chart" />
    <!-- <chartjs-bar v-for="(item, index) in types" :key="index" :backgroundcolor="item.bgColor" :beginzero="beginZero"
      :bind="true" :bordercolor="item.borderColor" :data="item.data" :datalabel="item.dataLabel" :labels="labels"
      :target="barId" :option="options" /> -->
  </div>
</template>

<script>
var MyStatus;
  import axios from 'axios'
  export default {
    props: ['barId'],
    name: 'ChartMandiri',
    data() {
      return {
        chart: {
          labels: ['January', 'February'],
          datasets: [{
            label: 'Dataset 1',
            backgroundColor: 'red',
            data: [
              2, 6
            ]
          }, {
            label: 'Dataset 2',
            backgroundColor: 'blue',
            data: [
              2, 6
            ]
          }, {
            label: 'Dataset 3',
            backgroundColor: 'green',
            data: [
              2, 6
            ]
          }],
          type: "bar",
          options: {
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
      }
    },
    methods: {
      async loadData(tipe) {
        let resp = await axios.get('v1/chart/pcr?tipe=' + tipe)        
        this.chart.labels = resp.data.label;
        this.chart.datasets = resp.data.data;
        this.setChart();
      },
      setChart(){
        var ctx = document.getElementById("chart").getContext("2d");
        let chartData = {
          labels:this.chart.labels,
          datasets: this.chart.datasets
        };

        MyStatus = new Chart(ctx, {
          type: this.chart.type,
          data: chartData,
          options: this.chart.options
        });
      }
    },
    created() {
        this.loadData('Daily');
    },
    mounted() {
        this.$bus.$on('refresh-chart-pcr', (tipe) => {
        MyStatus.destroy();
        setTimeout(() => {
          this.loadData(tipe)
        }, 500);
      })
    }
  };
</script>