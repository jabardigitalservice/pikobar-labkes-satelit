<template>
  <div class="ibox">
    <div v-if="title" class="ibox-title">
          <slot name="header"></slot>
          <h5>{{title}}</h5>
        <div class="ibox-tools">
          <slot name="tools" v-if="hasToolsSlot"></slot>
          <template v-if="!hasToolsSlot">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
            <!-- <a class="close-link">
                <i class="fa fa-times"></i>
            </a> -->
          </template>
        </div>
    </div>

    <div class="ibox-content">
      <slot />
    </div>
  </div>
</template>

<script>
export default {
  name: 'Ibox',

  props: {
    title: { type: String, default: null }
  },

  computed:{
    hasToolsSlot() {
        return this.$slots['tools']
    },
    hasTitleSlot() {
      return this.$slots['title']
    }
  }
}
</script>
