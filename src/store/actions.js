import Axios from 'axios'

export const actions = {
    SAVE_SETTINGS: ( { commit }, payload ) => {
        commit( 'SAVING' )
        let url = vappAdminLocalizer.apiUrl + '/api/v1/settings';
        let config = {
            headers: {
              'X-WP-Nonce': vappAdminLocalizer.VUE_NONCE,
            }
        }
        Axios.post( url, {
            numrows: payload.numrows,
            humandate : payload.humandate,
            emails : payload.emails,
        }, config )
        .then( ( response ) => {
            console.log(response.data)
            commit( 'SAVED' )
        } )
        .catch( ( error ) => {
            console.log( error )
        } )
    },

    FETCH_SETTINGS: ( { commit }, payload ) => {
        let url = vappAdminLocalizer.apiUrl + '/api/v1/settings';
        let config = {
            headers: {
              'X-WP-Nonce': vappAdminLocalizer.VUE_NONCE,
            }
        }
        Axios.get( url, config )
        .then( ( response ) => {
            payload = response.data
            commit( 'UPDATE_SETTINGS', payload )
        } )
        .catch( ( error ) => {
            console.log( error )
        } )
    },

    FETCH_EXTERNAL_API: ( { commit }, payload ) => {
        let url = vappAdminLocalizer.apiUrl + '/api/v1/external';
        let config = {
            headers: {
              'X-WP-Nonce': vappAdminLocalizer.VUE_NONCE,
            }
        }
        Axios.get( url, config )
        .then( ( response ) => {
            payload = JSON.parse(response.data);
            console.log(payload);
            // commit( 'UPDATE_TABLE', payload )
        } )
        .catch( ( error ) => {
            console.log( error )
        } )
    },
    
    REFRESH_API_DATA: ( { commit }, payload ) => {
        let url = vappAdminLocalizer.apiUrl + '/api/v1/external/refresh';
        let config = {
            headers: {
              'X-WP-Nonce': vappAdminLocalizer.VUE_NONCE,
            }
        }
        Axios.get( url, config )
        .then( ( response ) => {
            payload = JSON.parse(response.data);
            console.log(payload);
            // commit( 'UPDATE_TABLE', payload )
        } )
        .catch( ( error ) => {
            console.log( error )
        } )
    },
}