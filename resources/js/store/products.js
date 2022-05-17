import axios from "axios";
import router from "../router/router";

export const products = {
    namespaced: true,
    state: () => ({
        products: {}
    }),
    mutations: {
        products(state, products) {
            state.products = products;
        },

        addNew(state, product) {
            state.products.data.unshift(product);
        },

        update(state, updatedProduct) {

            let products = state.products.data;

            //Find index of specific object using findIndex method.
            const objIndex = products.findIndex(( product => product.id === updatedProduct.id ));
            products[objIndex] = updatedProduct;


        },
        delete(state, id) {
            state.products.data = state.products.data.filter(product => product.id !== id);
        },
    },

    getters: {
        products(state) {
            return state.products;
        }
    }
};

