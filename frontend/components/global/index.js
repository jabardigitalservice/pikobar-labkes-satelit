import Vue from 'vue'
import vSelect from 'vue-select'

Vue.component('v-select', vSelect)
import 'vue-select/dist/vue-select.css';
const requireContext = require.context('./', false, /.*\.(vue)$/)

requireContext.keys().forEach((file) => {
  const Component = requireContext(file).default

  if (Component.name) {
    Vue.component(Component.name, Component)
  }
})

import Multiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.min.css';
Vue.component('multiselect', Multiselect)