import Vue from 'vue'
 
// As a plugin
// import VueMask from 'v-mask'
// Vue.use(VueMask);
 
// Or as a directive
import { VueMaskDirective } from 'v-mask'
Vue.directive('mask', VueMaskDirective);
 
// Or only as a filter
import { VueMaskFilter } from 'v-mask'
Vue.filter('VMask', VueMaskFilter)
