import Vue from 'vue';
import toastr from 'toastr'
// import toastr from '../assets/js/toastr/toastr.min'


Vue.use(toastr);
window.toastr = toastr;


toastr.options.closeButton = true;
toastr.options.timeOut = 0;
toastr.options.extendedTimeOut = 15000;
toastr.options.progressBar = true;
toastr.options.tapToDismiss = false;

toastr.options.showMethod = 'slideDown';
toastr.options.hideMethod = 'slideUp';
toastr.options.closeMethod = 'slideUp';


