<template>
  <div class="wrapper wrapper-content">
    <portal to="title-name">PIKOBAR</portal>
    <portal to="title-action"></portal>

    <div class="row">
      <div class="col-lg-12">
        Anda tidak memiliki akses terhadap fitur ini
      </div>
    </div>
  </div>
</template>
 
<script>
export default {
  data() {
    return {
      params: {
        nama: null
      }
    };
  },
  head() {
    return { title: "Error" };
  }
};
</script>
