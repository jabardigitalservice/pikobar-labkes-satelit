<template>
<div class="gray-bg" style="height:100%;position:fixed;width:100%">
  <nuxt />
</div>
</template>

<style lang="scss">
body{
  background:#f3f3f4
}
.gray-bg {
  height: 100%
}
.simple-layout {
  color: #636b6f;
  height: 100vh;
  font-weight: 100;
  position: relative;

  .links > a {
    color: #636b6f;
    padding: 0 25px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: .1rem;
    text-decoration: none;
    text-transform: uppercase;
  }
}
</style>
