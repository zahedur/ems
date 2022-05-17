import axios from "axios";
import router from "../router/router";

export const brands = {
    namespaced: true,
    state: () => ({
        brands: {}
    }),
    mutations: {
        brands(state, brands) {
            state.brands = brands;
        },

        addNew(state, brand) {
            state.brands.unshift(brand);
        },

        update(state, updatedBrand) {
            let brands = state.brands;
            //Find index of specific object using findIndex method.
            const objIndex = brands.findIndex(( brand => brand.id === updatedBrand.id ));
            brands[objIndex] = updatedBrand;
        },
        delete(state, id) {
            state.brands = state.brands.filter(brand => brand.id !== id);
        },
    },

    actions: {
        getBrands({ commit }) {
            axios.get('brands')
                .then(res => {
                    if (res.data.success) {
                        commit('brands', res.data.brands);
                    }
                })
                .catch(err => {
                    if (err.response) {
                        commit('messages/errorMsg', err.response.data.message, { root: true })
                    }
                });
        },
    },
    getters: {
        brands(state) {
            return state.brands;
        }
    }
};

