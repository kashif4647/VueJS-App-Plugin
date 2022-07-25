<template>
    <div id="vapp-general-setting-tab" class="tab-container">
        <h2>Settings Form</h2>
        <form id="vapp-general-setting-form" @submit="saveSettings">
            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <th scope="row">
                            <label for="numrows">Num Rows</label>
                        </th>
                        <td>
                            <input id="numrows" class="regular-text" type="number" v-model="formData.numrows" min="1" max="5">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="humandate">Human Date</label>
                        </th>
                        <td>
                            <input type="checkbox" v-model="formData.humandate" v-bind:id="formData.id">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="emails">Emails</label>
                        </th>
                        <td>
                            <input id="emails" class="regular-text" type="email" v-model="formData.emails">
                        </td>
                    </tr>
                </tbody>
            </table>
            <p class="submit">
                <button type="submit" class="button button-primary">{{ loadingText }}</button>
            </p>
        </form>
        <div class="clear"></div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
export default {
    name: 'GeneralTab',

    data() {
        return {}
    },

    mounted() {
        this.fetchSettings()
    },

    computed: {
        ...mapGetters([ 'GET_GENERAL_SETTINGS', 'GET_LOADING_TEXT' ]),

        formData: {
            get() {
                return this.GET_GENERAL_SETTINGS
            },
        },

        loadingText: {
            get() {
                return this.GET_LOADING_TEXT
            }
        }
    },

    methods: {
        ...mapActions([ 'FETCH_SETTINGS', 'SAVE_SETTINGS'  ]),

        saveSettings(e) {
            e.preventDefault();
            this.SAVE_SETTINGS( this.formData )
        },

        fetchSettings() {
            this.FETCH_SETTINGS()
        }

    }
}
</script>

<style>

</style>