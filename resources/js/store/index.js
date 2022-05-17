import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);


import { auth } from "./auth";
import { rawMaterials } from "./rawMaterials";
import { brands } from "./brands";
import { messages } from "./messages";
import { helper } from "./helper";
import { products } from "./products";
import { general } from "./general";



export default new Vuex.Store( {
    modules: {
        auth,
        rawMaterials,
        brands,
        messages,
        helper,
        products,
        general
    }
});
