<template>
  <div id="wrapper" style="overflow-y: hidden;">
    <sidebar />

     <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <navbar/>
        </div>
        <div>
          <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-sm-6">
              <h2><portal-target name="title-name">Loading...</portal-target></h2>
              <inspinia-breadcrumbs></inspinia-breadcrumbs>
            </div>
            <div class="col-sm-6">
              <portal-target name="title-action"></portal-target>
            </div>
          </div>
        </div>
        <nuxt />
        <div class="footer">
            <div>
                <strong>Copyright</strong> &copy; 2020 |
                Pemerintah Provinsi Jawa Barat | GTPP COVID-19 | PIKOBAR
            </div>
        </div>
        <right-sidebar/>
    </div>
    
    
        

    <!-- <div class="container mt-4">
      <nuxt />
    </div> -->
  </div>
</template>

<script>
import Navbar from '~/components/Navbar'
import Sidebar from '~/components/Sidebar'
import Footer from '~/components/Footer'
import RightSidebar from '~/components/RightSidebar'

export default {
  components: {
    Navbar,
    Sidebar,
    Footer,
    RightSidebar
  }
}
</script>
<style>
body{
   background-color: #2f4050 !important;
}
</style>
