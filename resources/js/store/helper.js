export const helper = {
    namespaced: true,
    state: () => ({
        loading: false,
        sidebar: false
    }),
    mutations: {
        loading(state, value) {
            state.loading = value;
        },
        sidebar(state, value) {
            state.sidebar = value;
        },
    },
    getters: {
        loading(state) {
            return state.loading;
        },
        sidebar(state) {
            return state.sidebar;
        },
    }
};
