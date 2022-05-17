<template>
    <div>
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Salaries</h3>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><router-link :to="{ name: 'dashboard' }">Dashboard</router-link></li>
                                <li class="breadcrumb-item active" aria-current="page">Salaries</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content">
            <div class="row" id="table-bordered">
                    <div class="col-md-7 order-2 order-md-1">
                        <div class="card">
                            <div class="card-header py-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h4 class="card-title">All Salaries List</h4>
                                </div>
                            </div>
                            <div class="card-content p-4">
                                <div v-if="loading" class="d-flex justify-content-center align-items-center">
                                    <div class="loading">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                                <div v-else>
                                    <div v-if="salaries.data && salaries.data.length" >
                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-0">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th>Name</th>
                                                    <th class="text-end">Amount</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                    <tr v-for="(salary, i) in salaries.data">
                                                        <td class="text-center">{{ i+salaries.from }}</td>
                                                        <td>{{ salary.name }}</td>
                                                        <td class="text-end">{{ salary.amount }} {{ general.currency_symbol }}</td>
                                                        <td class="text-center">
                                                            <button class="btn btn-light dropdown-toggle btn-sm" type="button" id="actions" data-bs-toggle="dropdown" aria-expanded="false">
                                                                Action
                                                            </button>
                                                            <ul class="dropdown-menu shadow dropdown-menu-actions" aria-labelledby="actions">
                                                                <li><button class="dropdown-item d-flex align-items-center" type="button" @click="edit(salary.id)"><i class="bi bi-pencil me-2"></i> Edit</button></li>
                                                                <li><button class="dropdown-item d-flex align-items-center" type="button" @click="deleteItem(salary.id)"><i class="bi bi-trash me-2"></i> Delete</button></li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <Pagination v-if="isPaginate(salaries)" :links="salaries.links" :callBack="getLeaves" />

                                    </div>
                                    <div v-else class="empty text-center">
                                        <h5>Empty</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 order-1 order-md-2">
                        <div class="card">
                            <div class="card-header py-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h4 v-if="isEdit" class="card-title">Update Leave</h4>
                                    <h4 v-else class="card-title">Add New Leave</h4>
                                </div>
                            </div>
                            <div class="card-content p-4">
                                <form v-on="isEdit ? { submit: update } : { submit: save}">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" v-model="form.name" id="name" class="form-control" placeholder="Type department name">
                                        <span v-if="form.errors.has('name')" v-html="form.errors.get('name')" class="text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="amount" class="form-label">Amount</label>
                                        <input type="text" v-model="form.amount" id="amount" class="form-control" placeholder="Type the number of days">
                                        <span v-if="form.errors.has('amount')" v-html="form.errors.get('amount')" class="text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <button v-if="isEdit" type="submit" class="btn btn-primary">Update</button>
                                        <button v-if="isEdit" @click.prevent="cleanForm" type="button" class="btn btn-primary">Cancel</button>
                                        <button v-else type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</template>

<script>
import Pagination from "../../components/Pagination";
import axios from "axios";
export default {
    name: "salaries",
    components: { Pagination },
    data() {
        return {
            form: new Form({
                name: '',
                amount: 0
            }),
            isEdit: false,
            editId: '',
            salaries: []
        }
    },

    mounted() {
        this.getSalaries()
    },

    methods: {
        getSalaries(page = 1) {
            this.startLoading();
            axios.get(`salaries?page=${ page }`)
                .then(res => {
                    if (res.data.status) {
                        this.salaries = res.data.salaries;
                    }
                    this.endLoading()
                })
                .catch(err => {
                    Toast.fire('Error', 'Something went wrong!', 'error');
                    this.endLoading()
                });
        },

        save(e) {
            e.preventDefault();
            this.form.post('salaries/save')
                .then(res => {
                    if (res.data.status) {
                        this.salaries.data.unshift(res.data.salary);
                        Toast.fire('Success', res.data.message, 'success');
                        this.cleanForm()
                    }
                })
                .catch(err => {
                    this.error(err);
                    Toast.fire('Error', 'Something went wrong!', 'error');
                });
        },

        edit(id) {
            let data = this.salaries.data.filter(salary => salary.id === id);
            this.form.name = data[0].name;
            this.form.day = data[0].day;
            this.form.id = id;
            this.isEdit = true;
            this.editId = id;
        },

        update(e) {
            e.preventDefault();
            this.form.post(`salaries/update`)
                .then(res => {
                    if (res.data.status) {
                        let updated = res.data.salary;
                        let salaries = this.salaries.data;

                        //Find index of specific object using findIndex method.
                        const objIndex = salaries.findIndex(( salary => salary.id === updated.id ));
                        salaries[objIndex] = updated;
                        Toast.fire('Success', res.data.message, 'success');
                        this.cleanForm()
                    }
                })
                .catch(err => {
                    this.error(err);
                    Toast.fire('Error', 'Something went wrong!', 'error');
                });
        },

        deleteItem(id) {
            this.confirm(() => {
                axios.post(`salaries/delete`, {id: id})
                    .then(res => {
                        if (res.data.status){
                            this.salaries.data = this.salaries.data.filter(salary => salary.id !== id);
                            Toast.fire('Success', res.data.message, 'success');
                        }else{
                            Toast.fire('Error', res.data.message, 'error');
                        }
                    })
                    .catch(err => {
                        this.error(err);
                        Toast.fire('Error', 'Something went wrong!', 'error');
                    });
            })
        },

        cleanForm() {
            this.form.name = '';
            this.form.day = '';
            this.isEdit = false;
            this.editId = '';
        }
    },

}
</script>

<style scoped>

</style>
