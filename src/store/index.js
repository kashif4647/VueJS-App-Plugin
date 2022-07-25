import Vue from 'vue'
import Vuex from 'vuex'

import { getters } from './getters'
import { mutations } from './mutations'
import { actions } from './actions'

Vue.use( Vuex )

export default new Vuex.Store({
    state: {
        settings: {
            general: {
                numrows: '',
                humandate: '',
                emails: ''
            }
        },
        loadingText: 'Save Settings'
    },
    actions,
    getters,
    mutations
})