<template>
    <div>
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Report by Date</h3>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><router-link :to="{ name: 'dashboard' }">Dashboard</router-link></li>
                                <li class="breadcrumb-item active" aria-current="page">Reports</li>
                                <li class="breadcrumb-item active" aria-current="page">by Date</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content">

            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-content p-4">
                            <div class="search d-flex justify-content-center">
                                <form @submit.prevent="getReport">
                                    <div class="d-flex align-items-center justify-content-center flex-wrap gap-2">
                                        <datepicker v-model="searchDate" :clear-button="true" :input-class="'form-control'" id="date" placeholder="Date" :format="customFormatter"></datepicker>
                                        <button type="submit" class="btn btn-primary ms-2">
                                            <span class="d-flex align-items-center justify-content-center">
                                                <span>Search</span>
                                                <i class="bi bi-search ms-2"></i>
                                            </span>
                                        </button>
                                    </div>
                                    <span v-if="searchError" class="text-danger">{{ searchError }}</span>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="loading" class="d-flex justify-content-center align-items-center">
                <div class="loading">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div v-else class="row">
                <div v-if="searched" class="col-md-12 order-2 order-md-1">
                    <div class="export-print d-flex justify-content-end mb-1">
                        <button type="button" class="btn btn-primary btn-sm" @click="print">Print</button>
                        <button type="button" class="btn btn-primary btn-sm ms-1" @click="generateReport">Export Pdf</button>
                    </div>
                    <vue-html2pdf
                        :show-layout="false"
                        :float-layout="false"
                        :enable-download="true"
                        :preview-modal="false"
                        :paginate-elements-by-height="1400"
                        :filename="myDateFormat(searchDate)"
                        :pdf-quality="2"
                        :manual-pagination="false"
                        pdf-format="a4"
                        pdf-orientation="portrait"
                        pdf-content-width="100%"
                        ref="html2Pdf"
                    >
                        <section slot="pdf-content">
                            <div class="card" id="report-result">
                                <div class="card-content p-4">
                                    <div v-if="reportProducts && reportProducts.length">
                                        <div class="searched-date mb-2">
                                            <strong>Date: </strong> {{ myDateFormat(searchDate)}}
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-0">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th>Name</th>
                                                    <th class="text-center">Materials</th>
                                                    <th class="text-center">Date</th>
                                                    <th class="text-end">Total Amount</th>
                                                    <th class="text-center">Obtained Quantity</th>
                                                    <th class="text-end">Price Per Unit</th>
                                                    <th class="text-end">Total Income</th>
                                                    <th class="text-end">Profit</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(product, i) in reportProducts" :key="i">
                                                        <td class="text-center">{{ i+1 }}</td>
                                                        <td>{{ product.name }}</td>
                                                        <td class="text-center">{{ product.materials.length }}</td>
                                                        <td class="text-center">{{ myDateFormat(product.date) }}</td>
                                                        <td class="text-end">{{ limitAfterDecimal(product.amount, 2) }} {{ general.currency_symbol }}</td>
                                                        <td class="text-center">{{ product.obtained_quantity }} {{ product.unit_id ? product.unit.name : '' }}</td>
                                                        <td class="text-end">{{ product.price_per_unit }} {{ general.currency_symbol }}</td>
                                                        <td class="text-end">{{ limitAfterDecimal(product.total_income, 2) }} {{ general.currency_symbol }}</td>
                                                        <td class="text-end">{{ limitAfterDecimal(product.total_income - product.amount, 2) }} {{ general.currency_symbol }}</td>

                                                    </tr>
                                                    <tr class="bg-light">
                                                        <td class="text-end" colspan="5"><strong>Total: {{ limitAfterDecimal(totalExpense, 2) }} {{ general.currency_symbol }}</strong></td>
                                                        <td class="text-end" colspan="3"><strong>Total: {{ limitAfterDecimal(totalIncome, 2) }} {{ general.currency_symbol }}</strong></td>
                                                        <td class="text-end"><strong>Total: {{ limitAfterDecimal(profit, 2) }} {{ general.currency_symbol }}</strong></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div v-else class="empty text-center">
                                        <h5>Not found</h5>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </vue-html2pdf>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';
import VueHtml2pdf from 'vue-html2pdf'
export default {
    name: "reportDate",
    components: { Datepicker, VueHtml2pdf },
    data() {
        return {
            reportProducts: [],
            totalExpense: 0,
            totalIncome: 0,
            profit: 0,
            searchDate: '',
            searchError: '',
            searched: false,
        }
    },

    methods: {
        getReport() {
            this.searchError = '';
            if (!this.searchDate){
                this.searchError = 'Date field is required for search'
            }else {
                this.searched = true;
                const date = this.serverDateFormat(this.searchDate)
                this.startLoading();
                axios.post('reports/date', {date: date})
                    .then(res => {
                        if (res.data.status) {
                            let report = res.data.report;
                            this.reportProducts = report.products;
                            this.totalExpense = report.totalExpense;
                            this.totalIncome = report.totalIncome;
                            this.profit = report.profit;
                        }
                        this.endLoading();
                    })
                    .catch(err => {
                        this.error(err)
                        this.endLoading();
                        Toast.fire('Error', 'Something went wrong!', 'error');
                    })
            }
        },

        generateReport () {
            this.$refs.html2Pdf.generatePdf()
        },

        async print () {
            await this.$htmlToPaper('report-result');
        }


    }
}
</script>

<style scoped>

</style>
