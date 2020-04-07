<template>
  <card :title="$t('home')">
    TEST
  </card>
</template>

<script>
export default {

  head () {
    return { title: this.$t('home') }
  },
  mounted () {
      console.log(this.$route)
  }

}
</script>
