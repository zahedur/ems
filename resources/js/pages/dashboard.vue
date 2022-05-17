<template>
    <div>
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Dashboard</h3>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active"><router-link :to="{ name: 'dashboard' }">Dashboard</router-link></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content">
            <div class="row">
                <div class="col-lg-6 col-xl-4 col-xxl-3 mb-4">
                    <dashboardCard title="Total Employees" :value="100" :icon='`<i class="fab fa-product-hunt"></i>`' />
                </div>
            </div>


        </div>
    </div>
</template>

<script>

import dashboardCard from '../components/dashboardCard';
export default {
    name: "Dashboard",
    components: { dashboardCard },
    data() {
        return {
            todayProducts: 0,
            todayExpense: 0,
            todayIncome: 0,
            todayProfit: 0,

            thisMonthProducts: 0,
            thisMonthExpense: 0,
            thisMonthIncome: 0,
            thisMonthProfit: 0,

            thisYearProducts: 0,
            thisYearExpense: 0,
            thisYearIncome: 0,
            thisYearProfit: 0,

            totalProducts: 0,
            totalExpense: 0,
            totalIncome: 0,
            totalProfit: 0,

            totalMaterials: 0,
            totalReadyProduct: 0,
        }
    },

    mounted() {
        this.getDashboardData();
    },

    methods: {
        getDashboardData() {
            axios.get('dashboard')
                .then(res => {
                    if (res.data.status){
                        const data = res.data;
                        this.todayProducts =  this.limitAfterDecimal(data.todayProducts, 2);
                        this.todayExpense =  this.limitAfterDecimal(data.todayExpense, 2);
                        this.todayIncome =  this.limitAfterDecimal(data.todayIncome, 2);
                        this.todayProfit =  this.limitAfterDecimal(data.todayProfit, 2);

                        this.thisMonthProducts =  this.limitAfterDecimal(data.thisMonthProducts, 2);
                        this.thisMonthExpense =  this.limitAfterDecimal(data.thisMonthExpense, 2);
                        this.thisMonthIncome =  this.limitAfterDecimal(data.thisMonthIncome, 2);
                        this.thisMonthProfit =  this.limitAfterDecimal(data.thisMonthProfit, 2);

                        this.thisYearProducts =  this.limitAfterDecimal(data.thisYearProducts, 2);
                        this.thisYearExpense =  this.limitAfterDecimal(data.thisYearExpense, 2);
                        this.thisYearIncome =  this.limitAfterDecimal(data.thisYearIncome, 2);
                        this.thisYearProfit =  this.limitAfterDecimal(data.thisYearProfit, 2);

                        this.totalProducts =  this.limitAfterDecimal(data.totalProducts, 2);
                        this.totalExpense =  this.limitAfterDecimal(data.totalExpense, 2);
                        this.totalIncome =  this.limitAfterDecimal(data.totalIncome, 2);
                        this.totalProfit =  this.limitAfterDecimal(data.totalProfit, 2);

                        this.totalMaterials =  this.limitAfterDecimal(data.totalMaterials, 2);
                        this.totalReadyProduct =  this.limitAfterDecimal(data.totalReadyProduct, 2);
                    }
                })
        }
    }
}
</script>

<style scoped>

</style>
