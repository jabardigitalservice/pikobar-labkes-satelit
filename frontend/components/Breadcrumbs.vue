<template>
  <ol class="breadcrumb">
    <li class="breadcrumb-item" v-if="crumbs.length > 1">
      <a href="#" @click.prevent="$router.back()"><i class="fa fa-chevron-left"></i></a>
    </li>
    <li class="breadcrumb-item" v-if="crumbs.length <= 1">
      <strong><i class="fa fa-globe"></i></strong>
    </li>
    <li v-for="(item, i) in crumbs" :key="i" :class="item.classes">
      <nuxt-link :to="item.path" v-if="item.path">{{ item.name }}</nuxt-link>
      <strong v-if="!item.path">{{ item.name }}</strong>
    </li>
  </ol>
</template>

<script>
export default {
  name: 'inspinia-breadcrumbs',
  computed: {
    crumbs() {
      const crumbs = [];
      var parentRoute = this.$route.matched[0]
      // console.log('matched route',this.$route.matched)
      if (parentRoute) do {
        if (parentRoute.meta && parentRoute.meta.parentName) {
          parentRoute = this.$router.resolve({name: parentRoute.meta.parentName})
          const item = parentRoute.route
          const crumb = {};
          crumb.path = item.path;
          crumb.name = this.$i18n.t("route." + (item.name ? (item.name) : ('path'+item.path.replace(/\//,'.'))));
          crumb.classes = "breadcrumb-item";
          crumbs.unshift(crumb);
          parentRoute = item
        } else {
          parentRoute = null
        }
      } while (parentRoute)
      this.$route.matched.map((item, i, { length }) => {
        const crumb = {};
        crumb.path = item.path;
        crumb.name = this.$i18n.t("route." + (item.name ? (item.name) : ('path'+item.path.replace(/\//,'.'))));

        // is last item?
        if (i === length - 1) {
          crumb.path = null
          // is param route? .../.../:id
          if (item.regex.keys.length > 0) {
            crumb.path = this.$route.path;
            crumb.name = this.$i18n.t("route." + this.$route.name, [
              crumb.path.match(/[^/]*$/)[0]
            ]);
          }
          crumb.classes = "breadcrumb-item active";
        } else {
          crumb.classes = "breadcrumb-item";
        }

        crumbs.push(crumb);
      });

      return crumbs;
    }
  }
};
</script>

<style lang="scss" scoped>
// /deep/ a {
//   @include transition();
// }
</style>
