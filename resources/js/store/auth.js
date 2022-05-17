import axios from "axios";
import router from "../router/router";

export const auth = {
    namespaced: true,
    state: () => ({
        loggedIn: false,
        user: {},
        tokenInfo: {
            token: '',
            type: '',
        }
    }),
    mutations: {
        setAuthData(state) {
            const auth = JSON.parse(localStorage.getItem('auth'));
            if (auth) {
                state.loggedIn = true;
                state.tokenInfo.token = auth.access_token;
                state.tokenInfo.type = auth.token_type;
                state.user = auth.user;
                axios.defaults.headers.common['Authorization'] = `Bearer ${ auth.access_token }`
            } else {
                state.loggedIn = false;
                state.tokenInfo.token = '';
                state.tokenInfo.type = '';
                state.user = '';
                axios.defaults.headers.common['Authorization'] = ''
            }
        },
        user(state, user) {
            state.user = user;
        },
    },

    actions: {
        // isAuthenticated({ commit, dispatch } ) {
        //
        //     const auth = JSON.parse(localStorage.getItem('auth'));
        //     if (auth) {
        //         axios.defaults.headers.common['Authorization'] = `Bearer ${ auth.access_token }`;
        //     }
        //
        //     axios.post('auth/me')
        //         .then(res => {
        //             if (res.data.success) {
        //                 commit('user', res.data.user);
        //                 axios.get('settings/getSiteInfo')
        //                     .then(res => {
        //                         if (res.data.success) {
        //                             commit('siteInfo/siteInfo', res.data.siteInfo,  { root: true });
        //                         }
        //                     })
        //                     .catch(err => {
        //                         if (err.response) {
        //                             console.log(err.response.data.message);
        //                             commit('messages/errorMsg', err.response.data.message, { root: true });
        //                         }
        //                     })
        //             }
        //         })
        //         .catch(err => {
        //             dispatch('logout')
        //         })
        // },

        // refresh({ commit }) {
        //     axios.post('auth/refresh')
        //         .then(res => {
        //             localStorage.setItem('auth', JSON.stringify(res.data));
        //             this.$store.commit('auth/setAuthData');
        //         })
        //         .catch(err => {
        //             console.log(err.response)
        //         })
        // },

        logout({ commit }) {
            localStorage.removeItem('auth');
            commit('setAuthData');
            router.push({ name: 'login'});
        },

        AttemptToLogout({ commit }) {
            axios.post('auth/logout')
                .then(res => {
                    if (res.data.status){
                        localStorage.removeItem('auth');
                        commit('setAuthData');
                        router.push({ name: 'login'});
                    }
                })
                .catch(err => {
                    if (err.response.status === 401) {
                        localStorage.removeItem('auth');
                        commit('setAuthData');
                        router.push({ name: 'login'});
                    }
                })
        }
    },
    getters: {
        authenticated(state) {
            return state.loggedIn;
        },

        auth(state) {
            return state;
        },

        user(state) {
            return state.user;
        },

        tokenInfo(state) {
            return state.tokenInfo;
        }
    }
};

