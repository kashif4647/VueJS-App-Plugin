<template>
    <h2>Graph Page</h2>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
export default {
    name: 'Graph',

    data() {
        return {}
    },

    mounted() {
        this.fetchExternalAPIData()
    },

    methods: {
        ...mapActions([ 'REFRESH_API_DATA', 'FETCH_EXTERNAL_API' ]),

        refreshExternalAPIData(e) {
            e.preventDefault();
            this.REFRESH_API_DATA()
        },

        fetchExternalAPIData() {
            this.FETCH_EXTERNAL_API()
        }

    }
}
</script>

<style>

</style>