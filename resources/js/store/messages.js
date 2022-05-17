
export const messages = {
    namespaced: true,
    state: () => ({
        successMsg: '',
        errorMsg: '',
        warningMsg: '',
        infoMsg: '',
    }),
    mutations: {
        successMsg(state, message) {
            state.successMsg = message;
        },

        errorMsg(state, message) {
            state.errorMsg = message;
        },

        warningMsg(state, message) {
            state.warningMsg = message;
        },

        infoMsg(state, message) {
            state.infoMsg = message;
        }
    },

    actions: {

    },

    getters: {
        successMsg(state) {
            return state.successMsg;
        },

        errorMsg(state) {
            return state.errorMsg;
        },

        warningMsg(state) {
            return state.warningMsg;
        },

        infoMsg(state) {
            return state.infoMsg;
        }
    }
};

