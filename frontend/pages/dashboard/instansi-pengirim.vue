<template>
  <Ibox title="Instansi Pengirim">
    <div class="col-md-6 row mb-1">
      <button class="btn btn-default" :class="{active:tipe === 'fasyankes'}"
        @click="tipe = 'fasyankes'">Fasyankes</button>
      <button class="btn btn-default" :class="{active:tipe === 'kota'}" @click="tipe = 'kota'">Domisili</button>
    </div>
    <ajax-table :url="`/v1/dashboard/instansi-pengirim?type=${this.tipe}`" :oid="'instansi_pengirim'"
      ref="instansi_pengirim" :config="{
          autoload: true,
          has_number: true,
          has_entry_page: false,
          has_pagination: true,
          has_action: false,
          has_search_input: false,
          custom_header: '',
          default_sort: '',
          custom_empty_page: true,
          class: {
            table: [],
            wrapper: ['table-responsive'],
          }
        }" :rowtemplate="'tr-instansi-pengirim'" :columns="{
          instansi_pengirim: 'INSTANSI PENGIRIM',
          y : 'TOTAL',
        }" />
  </Ibox>
</template>

<script>
  export default {
    name: "instansi-pengirim",
    data() {
      return {
        data: {},
        tipe: 'fasyankes',
      };
    },
    watch: {
      "tipe": function (newVal, oldVal) {
        setTimeout(() => {
          this.$bus.$emit("refresh-ajaxtable", "instansi_pengirim");
        }, 500);
        console.log('tipe = ', this.tipe)
      },
    }
  };
</script>

<style scoped>
  .instansi-pengirim {
    color: #1DB0E6;
    background-color: #ffffff;
    padding: 10px;
    font-weight: bold;
    border: 1px solid #1DB0E6;
  }

  .instansi-pengirim:active {
    color: #ffffff;
    background-color: #1DB0E6;
    padding: 10px;
    font-weight: bold;
    border: 1px solid #1DB0E6;
  }
</style>