import Vue from 'vue';
import { mapGetters } from 'vuex'
import {products} from "../store/products";
import moment from "moment";

Vue.mixin({
    data() {
        return {
            years: [],
            months: [
                {id: 1, name: 'January'},
                {id: 2, name: 'February'},
                {id: 3, name: 'March'},
                {id: 4, name: 'April'},
                {id: 5, name: 'May'},
                {id: 6, name: 'June'},
                {id: 7, name: 'July'},
                {id: 8, name: 'August'},
                {id: 9, name: 'September'},
                {id: 10, name: 'October'},
                {id: 11, name: 'November'},
                {id: 12, name: 'December'}
            ],
        }
    },

    mounted() {
        this.setYears();
    },

    methods: {
        getPageNumber(pageUrl) {
            let url = new URL(pageUrl);
            return url.searchParams.get("page");
        },

        isPaginate(data) {
            // let url1 = new URL(pageUrl1);
            // console.log(url1)
            // let Number1 = url1.searchParams.get("page");
            //
            // let url2 = new URL(pageUrl2);
            // let Number2 = url2.searchParams.get("page");
            //
            // return Number1 !== Number2;

            return data.per_page < data.total;
        },

        getFirstLetter(text) {
            if (text.length){
                return text.split(' ').map(x => x[0].toUpperCase()).join('');
            }else{
                return  ''
            }
        },

        limitAfterDecimal (value, limit) {
            return (Math.floor(value * 100) / 100).toFixed(limit)
        },

        customFormatter(date) {
            return moment(date).format('D/MM/YYYY');
        },

        today() {
            let date= new Date();
            return date.getDate() +'/'+date.getMonth()+'/'+date.getFullYear();
        },

        myDateFormat(date) {
            return moment(date).format('DD/MM/YYYY')
        },

        serverDateFormat(date) {
            return moment(date).format('YYYY-MM-DD')
        },

        getMonthName(id) {
            const months = [
                {id: 1, name: 'January'},
                {id: 2, name: 'February'},
                {id: 3, name: 'March'},
                {id: 4, name: 'April'},
                {id: 5, name: 'May'},
                {id: 6, name: 'June'},
                {id: 7, name: 'July'},
                {id: 8, name: 'August'},
                {id: 9, name: 'September'},
                {id: 10, name: 'October'},
                {id: 11, name: 'November'},
                {id: 12, name: 'December'}
            ];

            const month = months.filter(m => m.id === id);
            return month[0].name;
        },

        confirm(callback){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: '',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    callback();
                }

            })
        },

        setYears() {
            const from = 2000;
            const now = new Date().getFullYear();
            let years = []
            for(let i = from; i <= now; i++){
                years.push(i);
            }
            this.years = years.reverse();
        },

        success(message) {
            this.$store.commit('messages/successMsg', message);
        },
        error(err) {
            if (err.response) {
                this.$store.commit('messages/errorMsg', err.response.data.message);
            }
        },
        warning(message) {
            this.$store.commit('messages/warningMsg', message);
        },
        info(message) {
            this.$store.commit('messages/infoMsg', message);
        },

        startLoading() {
            this.$store.commit('helper/loading', true);
        },
        endLoading() {
            this.$store.commit('helper/loading', false);
        },

    },

    computed: {
        ...mapGetters({
            auth: 'auth/auth',
            user: 'auth/user',
            authenticated: 'auth/authenticated',
            general: 'general/general',

            //Messages
            successMsg: 'messages/successMsg',
            errorMsg: 'messages/errorMsg',
            warningMsg: 'messages/warningMsg',
            infoMsg: 'messages/infoMsg',
            loading: 'helper/loading',

            //Raw materials
            rawMaterials: 'rawMaterials/rawMaterials',
        })
    }
});

