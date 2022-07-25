import Vue from 'vue'
import Vuex from 'vuex'
import Router from 'vue-router'
import store from '../store/index'
import App from './App.vue'
import Graph from './components/pages/Graph.vue'
import Table from './components/pages/Table.vue'
import TabNavigation from './components/tabs/Navigation.vue'
import GeneralTab from './components/tabs/GeneralTab.vue'
import VueRouter from 'vue-router'

Vue.use( Vuex )
Vue.use( Router )

const routes = [
    {
        path: '/', components: { default: Table },
    },
    {
        path: '/graph', components: { default: Graph },
    },
    {
        path: '/settings', components: { default: GeneralTab, tab: TabNavigation },
    },
]

const router = new VueRouter({
    routes,
})

new Vue({
    el: '#vapp-admin-app',
    store,
    router,
    render: h => h( App )
})