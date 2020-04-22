<template>

      <div class="wrapper wrapper-content">
        <portal to="title-name">
          Dashboard
        </portal>
        <portal to="title-action">
          <div class="title-action">
            <a href="" class="btn btn-primary">Dashboard</a>
          </div>
        </portal>

          <div class="row">
            <div class="col-lg-12">
             
            </div>
        </div>
      </div>
</template>
 
<script>
export default {
  middleware: 'auth',
  data(){
    return{
       params: {
          nama: null
        },
    }
  },
  head () {
    return { title: this.$t('home') }
  }
}
</script>
