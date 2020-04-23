<template>
  <div class="wrapper wrapper-content">
    <portal to="title-action">
      <div class="title-action">
        <router-link v-for="tab in tabs" :key="tab.route"
          :to="{ name: tab.route }" class="btn" active-class="btn-primary" inactive-class="btn-default">
          <fa :icon="tab.icon" fixed-width />
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
    tabs () {
      return [
        {
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
