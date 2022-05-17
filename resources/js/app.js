require('./bootstrap');
window.Vue = require('vue').default;

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('app', require('./layouts/App.vue').default);


//vue router
import router from "./router/router";
import store from './store';

//Plugins
import './plugins/axios';
import './plugins/sweetAlert2';
import './plugins/mixin';
import './plugins/vueHtmlToPaper'


//vform
import vform from 'vform';
window.Form = vform;

//Toaster
import './plugins/toastr'

const app = new Vue({
    el: '#app',
    router,
    store
});
