<template>
    <div>
      <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-4">
          <h2>Dashboard</h2>
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <nuxt-link to="/">Home</nuxt-link>
            </li>
            <li class="breadcrumb-item active">
              <strong>Dashboard</strong>
            </li>
          </ol>
        </div>
        <div class="col-sm-8">
          <div class="title-action">
            <a href="" class="btn btn-primary">Dashboard</a>
          </div>
        </div>
      </div>

      <div class="wrapper wrapper-content">
          <div class="row">
            <div class="col-lg-12">
             
            </div>
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
