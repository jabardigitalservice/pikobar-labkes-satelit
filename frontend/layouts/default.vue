<template>
  <div id="wrapper">
    <sidebar />

     <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <navbar/>
        </div>
        <nuxt />
        <div class="footer">
            <div>
                <strong>Copyright</strong> PIKOBAR &copy; 2020
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
