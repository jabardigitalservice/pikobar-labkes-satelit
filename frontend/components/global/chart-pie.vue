<template>
  <div>
    <chartjs-pie
        :bind="true"
        :datasets="datasets"
        :labels="labels"
        :option="option"
      />
  </div>
</template>

<script>
export default {
  data() {
    return {
      datasets: [
        {
          data: [10, 20, 40],
          backgroundColor: ["#f36e60", "#ffdb3b", "#185190"],
          hoverBackgroundColor: ["#fbd2cd", "#fef5c9", "#d1e3f7"]
        }
      ],
      labels: ["Foo", "Bar", "Baz"],
      option: {}
    };
  }
};
</script>