<template>
  <button class="btn btn-primary" href="#" @click="parentRefs[ajaxTableRef].export()" :disabled="isLoading">
    <template v-if="isLoading">
      <fa icon="spinner" class="fa-spin" fixed-width/> Download
    </template>
    <template v-else>
      <i class="fa fa-download"></i> Download
    </template>
  </button>
</template>

<script>
export default {
  name: 'DownloadExportButton',
  props: {loading: Boolean, action: Function, parentRefs: Object, ajaxTableRef: String},
  data() {
    return {
      isLoading: false,
    }
  },
  methods: {
    controlStatus(status, oid) {
      if (oid == this.ajaxTableRef || oid.replace('-','_') == this.ajaxTableRef) {
        if (status == 'start') {
          this.isLoading = false
        }
        if (status == 'end') {
          this.isLoading = false
        }
      }
    }
  },
  created () {
    this.$bus.$on('download-export',this.controlStatus)
  },
  beforeDestroy() {
    this.$bus.$off('download-export',this.controlStatus)
  },
}
</script>
