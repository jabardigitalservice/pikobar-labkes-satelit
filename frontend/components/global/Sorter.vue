<template>
  <span style="float: right">
    <fa v-show="sortColumn == columnName && sortDir == 'asc'" icon="caret-up"/>
    <fa v-show="sortColumn == columnName && sortDir == 'desc'" icon="caret-down"/>
  </span>
</template>

<script>
    export default {
        props: ['columnName','sortColumn','sortDir'],
    }
</script>
