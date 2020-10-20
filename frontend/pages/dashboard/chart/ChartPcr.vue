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
              label: 'Positif',
              backgroundColor: '#c41a1a',
              data: []
            }, {
              label: 'Negatif',
              backgroundColor: '#c4c2c2',
              data: []
            },
            {
              label: 'Inkonklusif',
              backgroundColor: '#403d3d',
              data: []
            },
            {
              label: 'Invalid',
              backgroundColor: '#32427b',
              data: []
            },
          ],
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
        this.chart.labels = resp.data.result.labels;
        this.chart.datasets[0].data = resp.data.result.positif;
        this.chart.datasets[1].data = resp.data.result.negatif;
        this.chart.datasets[2].data = resp.data.result.inkonklusif;
        this.chart.datasets[3].data = resp.data.result.invalid;
        this.setChart();
      },
      setChart() {
        var ctx = document.getElementById("chart").getContext("2d");
        let chartData = {
          labels: this.chart.labels,
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
      setTimeout(() => {
        this.loadData('Daily');
      }, 500);
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