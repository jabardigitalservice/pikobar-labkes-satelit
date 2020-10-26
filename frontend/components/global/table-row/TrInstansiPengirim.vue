<template>
  <tr>
    <td v-text="(pagination.page - 1) * pagination.perpage + 1 + index"></td>
    <td>
      {{ item.name }}
    </td>
    <td>
      {{ item.y }}
    </td>
  </tr>
</template>

<script>
  export default {
    props: ["item", "pagination", "rowparams", "index"],
    components: {},
    data() {
      return {};
    },
  };
</script>