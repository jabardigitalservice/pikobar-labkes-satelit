<template>
  <span style="float: right">
    <i class="fa fa-caret-up" v-show="sortColumn == columnName && sortDir == 'asc'"></i>
    <i class="fa fa-caret-down" v-show="sortColumn == columnName && sortDir == 'desc'"></i>
  </span>
</template>

<script>
    export default {
        props: ['columnName','sortColumn','sortDir'],
    }
</script>
