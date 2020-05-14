<template>
    <div >
      <canvas :id="barId" count="1" />
      <chartjs-bar
        v-for="(item, index) in types"
        :key="index"
        :backgroundcolor="item.bgColor"
        :beginzero="beginZero"
        :bind="true"
        :bordercolor="item.borderColor"
        :data="item.data"
        :datalabel="item.dataLabel"
        :labels="labels"
        :target="barId"
      />
    </div>
</template>

<script>
import axios from 'axios'
export default {
  props:['barId'],
  name:'ChartMandiri',
  data() {
    return {
      beginZero: true,
      labels: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
      // labels:[],
      types: [
        {
          bgColor: "#de98ab",
          borderColor: "0c0306",
          // data: [1, 3, 5, 7, 2, 4, 6],
          data:[],
          dataLabel: "Register"
        },
        // {
        //   bgColor: "#98ddde",
        //   borderColor: "030c0c",
        //   data: [1, 5, 2, 6, 3, 7, 4],
        //   dataLabel: "Baz"
        // }
      ]
    };
  },
  methods:{
      async loadData(){
          let resp = await axios.get('v1/chart/regis-rujukan')
          this.labels = resp.data.label;
          this.types[0].data = resp.data.value
          console.log(resp)
      }
  },
  created(){
    this.loadData();
  }
};
</script,>