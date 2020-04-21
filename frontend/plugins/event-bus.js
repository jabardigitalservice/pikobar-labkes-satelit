import Vue from 'vue'

export default function ({ app, store }) {
    app.$eventHub = new Vue()
    if (store) store.$eventHub = app.$eventHub
}