<template>
  <div class="wrapper wrapper-content">
    <portal to="title-action">
      <div class="title-action">
        <router-link v-for="tab in tabs" :key="tab.route" :to="{ name: tab.route }" class="btn" active-class="btn-info"
          inactive-class="btn-default">
          <i v-if="tab.icon === 'user'" class="fa fa-user" />
          <i v-if="tab.icon === 'lock'" class="fa fa-lock" />
          &nbsp;
          {{ tab.name }}
        </router-link>
      </div>
    </portal>
    <transition name="fade" mode="out-in">
      <router-view />
    </transition>
  </div>
</template>

<script>
  export default {
    middleware: 'auth',

    computed: {
      tabs() {
        return [{
            icon: 'user',
            name: this.$t('profile'),
            route: 'settings.profile'
          },
          {
            icon: 'lock',
            name: this.$t('password'),
            route: 'settings.password'
          }
        ]
      }
    }
  }
</script>

<style>
  .settings-card .card-body {
    padding: 0;
  }
</style>