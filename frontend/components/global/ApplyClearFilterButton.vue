<template>
  <div>
    <button class="btn btn-primary" @click="actionDoFilter" style="margin-right: 10px">
      <i class="fa fa-check" /> Apply Filter
    </button>
    <button class="btn btn-clear" @click="actionClearFilter" style="margin-right: 10px">
      <i class="fa fa-close" /> Clear Filter
    </button>
  </div>
</template>

<script>
  export default {
    name: 'ApplyClearFilterButton',
    props: {
      doFilter: Function,
      clearFilter: Function
    },
    data: function () {
      return {
        actionDoFilter: this.doFilter,
        actionClearFilter: this.clearFilter
      }
    }
  }
</script>