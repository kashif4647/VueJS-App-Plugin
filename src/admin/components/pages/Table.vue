<template>
    <h2>Table Page</h2>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
export default {
    name: 'Table',

    mounted() {
        this.fetchExternalAPIData()
    },

    methods: {
        ...mapActions([ 'FETCH_EXTERNAL_API' ]),

        fetchExternalAPIData() {
            this.FETCH_EXTERNAL_API()
        }

    }
}
</script>

<style>

</style>