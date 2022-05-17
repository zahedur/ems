export const general = {
    namespaced: true,
    state: () => ({
        appName: '',
        logo: '',
        title: '',
        description: '',
        currency_text: '',
        currency_symbol: '',
        version: '',
    }),

    mutations: {
        general(state, data) {
            state.appName = data.app_name;
            state.logo = data.logo;
            state.title = data.title;
            state.description = data.description;
            state.currency_text = data.currency_text;
            state.currency_symbol = data.currency_symbol;
            state.version = data.version;
        },
    },

    getters: {
        general(state) {
            return state
        }
    },

    actions: {
        generalData({ commit }) {
            axios.get('general').then(res => {
                if (res.data.status){
                    commit('general', res.data.general);
                }
            })
            .catch(err => {
                console.log(err);
            })
        },
    }
}
