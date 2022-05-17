<template>
    <div>
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Add Employees Salary</h3>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><router-link :to="{ name: 'dashboard' }">Dashboard</router-link></li>
                                <li class="breadcrumb-item active" aria-current="page">Add Employees Salary</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content">
            <div class="row justify-content-center">
                <div class="col-md-8 order-2 order-md-1">

                    <div class="card">
                        <div class="card-content p-4">
                            <div class="row justify-content-center mb-3 ">
                                <div class="col-lg-5">
                                    <form @submit.prevent="paidEmployee" class="p-2 rounded">
                                        <div class="">
                                            <div class="form-group">
                                                <label for="paidMethod">Month</label>
                                                <select class="form-control" v-model="form.month">
                                                    <option value="">--Select Month--</option>
                                                    <option v-for="month in months" :value="month.id">{{ month.name }}</option>
                                                </select>
                                                <span class="text-danger" v-if="form.errors.has('month')" v-html="form.errors.get('month')"></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="paidMethod">Year</label>
                                                <select class="form-control" v-model="form.year">
                                                    <option value="">--Select Year--</option>
                                                    <option v-for="year in years" :value="year">{{ year }}</option>
                                                </select>
                                                <span class="text-danger" v-if="form.errors.has('year')" v-html="form.errors.get('year')"></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="paidMethod">Method</label>
                                                <select class="form-select" v-model="form.paidMethod" @change="paidMethodChangeHandler" id="paidMethod">
                                                    <option value="all-employee">All Employee</option>
                                                    <option value="department-wise">Department Wise</option>
                                                    <option value="designation-wise">Designation Wise</option>
                                                    <option value="individual">Individual</option>
                                                </select>
                                                <span class="text-danger" v-if="form.errors.has('paidMethod')" v-html="form.errors.get('paidMethod')"></span>
                                            </div>
                                            <div v-if="form.paidMethod === 'department-wise'" class="form-group">
                                                <label for="method-department">Department</label>
                                                <select class="form-select" v-model="form.department" id="method-department">
                                                    <option value="">All Department</option>
                                                    <option v-if="departments.length" v-for="department in departments" :key="department.id" :value="department.id">{{ department.name }}</option>
                                                </select>
                                                <span class="text-danger" v-if="form.errors.has('department')" v-html="form.errors.get('department')"></span>
                                            </div>
                                            <div v-if="form.paidMethod === 'designation-wise'" class="form-group">
                                                <label for="method-designation">designation</label>
                                                <select class="form-select" v-model="form.designation" id="method-designation">
                                                    <option value="">All Designation</option>
                                                    <option v-if="designations.length" v-for="designation in designations" :key="designation.id" :value="designation.id">{{ designation.name }}</option>
                                                </select>
                                                <span class="text-danger" v-if="form.errors.has('designation')" v-html="form.errors.get('designation')"></span>
                                            </div>
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary ms-1">Paid</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="form.paidMethod === 'individual'" class="card">
                        <div class="card-content p-4">
                            <div class="text-center mb-2">
                                <span class="text-danger" v-if="form.errors.has('employees')" v-html="form.errors.get('employees')"></span>
                            </div>
                            <div class="search mb-3 d-flex justify-content-center">
                                <form @submit.prevent="getEmployees" class="p-2 rounded bg-light">
                                    <div class="d-flex align-items-center justify-content-end flex-wrap">
                                        <input v-model="search.id" class="form-control m-1" placeholder="ID" />
                                        <select class="form-select" v-model="search.department">
                                            <option value="">--Department--</option>
                                            <option v-if="departments.length" v-for="department in departments" :key="department.id" :value="department.id">{{ department.name }}</option>
                                        </select>
                                        <select class="form-select" v-model="search.designation">
                                            <option value="">--Designation--</option>
                                            <option v-if="designations.length" v-for="designation in designations" :key="designation.id" :value="designation.id">{{ designation.name }}</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary ms-1">
                                            <span class="d-flex align-items-center justify-content-center">
                                                <span>Search</span>
                                                <i class="bi bi-search ms-2"></i>
                                            </span>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div v-if="loading" class="d-flex justify-content-center align-items-center">
                                <div class="loading">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <div v-if="employees && employees.length">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-0">
                                            <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">ID</th>
                                                <th>Name</th>
                                                <th class="text-center">Email</th>
                                                <th class="text-center">Department</th>
                                                <th class="text-center">Designation</th>
                                                <th class="text-end">Salary</th>
                                                <th class="text-center">
                                                    <input type="checkbox" v-model="individualAllCheck" @change="individualAllCheckHandler">
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <tr v-for="(employee, i) in employees" :key="i">
                                                <td class="text-center">{{ i+1 }}</td>
                                                <td class="text-center">{{ employee.emp_id }}</td>
                                                <td>{{ employee.fname }} {{ employee.lname }}</td>
                                                <td class="text-center">{{ employee.email}}</td>
                                                <td class="text-center">{{ employee.department.name }}</td>
                                                <td class="text-center">{{ employee.designation.name }}</td>
                                                <td class="text-end">{{ employee.salary.amount }} {{ general.currency_symbol }}</td>
                                                <td class="text-center">
                                                    <input type="checkbox" v-model="form.employees" :value="employee.id">
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div v-else class="empty text-center my-5">
                                    <h5>Empty</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade" id="alreadyAdded" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle">Employee Details</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <i data-feather="x"></i>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div v-if="Object.keys(alreadyAdded).length" class="table-responsive ">
                                <table class="table table-bordered mb-0 bg-light">
                                    <thead>
                                        <tr>
                                            <th>Employee name (ID)</th>
                                            <th class="text-center">Month</th>
                                            <th class="text-center">Year</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="aa in alreadyAdded" :key="aa.id">
                                            <td>{{ aa.employee.fname }} {{ aa.employee.lname }} ({{ aa.employee.emp_id }})</td>
                                            <td>{{ aa.month }}</td>
                                            <td>{{ aa.year }}</td>
                                            <td>{{ aa.status }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary ml-1" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Pagination from "../../components/Pagination";
import Datepicker from 'vuejs-datepicker';
export default {
    name: "employeesSalary",
    components: { Pagination, Datepicker },
    data() {
        return {
            employees: [],
            search: {
                id: '',
                department: '',
                designation: ''
            },

            individualAllCheck: false,

            form: new Form( {
                month: '',
                year: '',
                paidMethod: 'all-employee',
                department: '',
                designation: '',
                employees: [],
            }),

            alreadyAdded: [],

            departments: [],
            designations: [],
            salaries: [],
            countries: [],
            leaves: [],
            modal: '',
        }
    },

    mounted() {
        this.getEmployees();
        this.getDepartments();
        this.getDesignations();
    },

    methods: {
        getEmployees() {
            const search = this.search;
            this.startLoading();
            axios.get(`employees/active-all?id=${search.id}&department=${search.department}&designation=${search.designation}`)
                .then(res => {
                    if (res.data.status){
                        this.employees = res.data.employees;
                    }
                    this.endLoading();
                })
                .catch(err => {
                    this.error(err)
                    this.endLoading();
                    Toast.fire('Error', 'Something went wrong!', 'error');
                })
        },

        getDepartments() {
            axios.get('departments/all').then(res => {
                if (res.data.status) {
                    this.departments = res.data.departments;
                }
            }).catch(err => {
                console.log(err);
                Toast.fire('Error', 'Something went wrong!', 'error');
            })
        },

        getDesignations() {
            axios.get('designations/all').then(res => {
                if (res.data.status) {
                    this.designations = res.data.designations;
                }
            }).catch(err => {
                console.log(err);
                Toast.fire('Error', 'Something went wrong!', 'error');
            })
        },

        paidMethodChangeHandler() {
            if (this.form.paidMethod !== 'individual'){
                this.individualAllCheck = false;
                this.form.employees = [];
            }
        },

        individualAllCheckHandler() {
            if (this.individualAllCheck){
                let employees = [];
                this.employees.map(emp => {
                    employees.push(emp.id);
                })
                this.form.employees = employees;
            }else{
                this.form.employees = [];
            }
        },

        async paidEmployee() {
            await this.form.post('employees/add-paid-salary').then(res => {
                if (res.data.status){
                    Toast.fire('Success', res.data.message, 'success');
                    if (res.data.already_added.length){
                        this.alreadyAdded = res.data.already_added;
                        let myModal = new bootstrap.Modal(document.getElementById('alreadyAdded'), {
                            keyboard: false,
                            //backdrop: true
                            //focus: false
                        })
                        myModal.toggle()
                    }
                    this.clearForm();
                }
            }).catch(err =>{
                console.log(err);
                Toast.fire('Error', 'Something went wrong!', 'error');
            })
        },


        clearForm() {
            this.form.reset();
        },

        clearEditForm() {
            this.editForm.reset();
        }
    }
}
</script>

<style scoped>

</style>
