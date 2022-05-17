<template>
    <div>
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>All Employees</h3>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><router-link :to="{ name: 'dashboard' }">Dashboard</router-link></li>
                                <li class="breadcrumb-item active" aria-current="page">All Employees</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content">
            <div class="row">
                <div class="col-md-12 order-2 order-md-1">
                    <div class="card">
                        <div class="card-header py-3">
                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                <h4 class="card-title">All Employees List</h4>
                                <button type="button" class="btn btn-primary" @click="addEmployee">Add New</button>
                            </div>
                        </div>
                        <div class="card-content p-4">
                            <div class="search mb-3 d-flex justify-content-end">
                                <form @submit.prevent="searchEmployee" class="p-2 rounded bg-light">
                                    <div class="d-flex align-items-center justify-content-end flex-wrap">
                                        <input v-model="search.id" class="form-control m-1" placeholder="ID" />
                                        <input v-model="search.email" class="form-control m-1" placeholder="Email" />
                                        <input v-model="search.phone" class="form-control m-1" placeholder="Phone" />
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
                                    <span v-if="searchError" class="text-danger">{{ searchError }}</span>
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
                                <div v-if="employees.data && employees.data.length">

                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-0">
                                            <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">ID</th>
                                                <th>Name</th>
                                                <th class="text-center">Email</th>
                                                <th class="text-center">Phone</th>
                                                <th class="text-center">Department</th>
                                                <th class="text-center">Designation</th>
                                                <th class="text-end">Salary</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <tr v-for="(employee, i) in employees.data" :key="i">
                                                <td class="text-center">{{ i+employees.from }}</td>
                                                <td class="text-center">{{ employee.emp_id }}</td>
                                                <td>{{ employee.fname }} {{ employee.lname }}</td>
                                                <td class="text-center">{{ employee.email}}</td>
                                                <td class="text-center">{{ employee.phone }}</td>
                                                <td class="text-center">{{ employee.department.name }}</td>
                                                <td class="text-center">{{ employee.designation.name }}</td>
                                                <td class="text-end">{{ employee.salary.amount }} {{ general.currency_symbol }}</td>
                                                <td class="text-center">
                                                    <span v-if="employee.status" class="text-success fw-bold">Active</span>
                                                    <span v-else class="text-danger fw-bold">Inactive</span>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-light dropdown-toggle btn-sm" type="button" id="actions" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <ul class="dropdown-menu shadow dropdown-menu-actions" aria-labelledby="actions">
                                                        <li>
                                                            <button class="dropdown-item d-flex align-items-center" type="button" @click="viewEmployee(employee)">
                                                                <i class="bi bi-eye me-2"></i> View Details
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <button class="dropdown-item d-flex align-items-center" type="button" @click="viewSalaryHistory(employee)">
                                                                <i class="bi bi-eye me-2"></i> View Salary History
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <button class="dropdown-item d-flex align-items-center" type="button" @click="editEmployee(employee)">
                                                                <i class="bi bi-pencil me-2"></i> Edit
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <button class="dropdown-item d-flex align-items-center" type="button" @click="deleteEmployee(employee.id)">
                                                                <i class="bi bi-trash me-2"></i> Delete
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <Pagination v-if="isPaginate(employees)" :links="employees.links" :callBack="getEmployees" />

                                </div>
                                <div v-else class="empty text-center my-5">
                                    <h5>Empty</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="viewEmployee" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-centered modal-dialog-scrollable"
                     role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle">Employee Details</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <i data-feather="x"></i>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div v-if="Object.keys(details).length" class="table-responsive ">
                                <table class="table table-bordered mb-0 bg-light">
                                    <tbody>
                                        <tr>
                                            <td class="text-end">ID</td>
                                            <td>{{ details.emp_id }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-end">First Name</td>
                                            <td>{{ details.fname }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-end">Last Name</td>
                                            <td>{{ details.lname }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-end">Email</td>
                                            <td>{{ details.email }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-end">Phone</td>
                                            <td>{{ details.phone }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-end">Address</td>
                                            <td>{{ details.address }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-end">state</td>
                                            <td>{{ details.state }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-end">zip_code</td>
                                            <td>{{ details.zip_code }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-end">country_id</td>
                                            <td>{{ details.country_id }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-end">salary_id</td>
                                            <td>{{ details.salary.amount }} {{ general.currency_symbol }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-end">Leaves</td>
                                            <td>
                                                <table class="table table-bordered bg-white" v-if="details.leaves.length">
                                                    <thead>
                                                        <tr>
                                                            <th>Leave Name</th>
                                                            <th class="text-center">Days</th>
                                                            <th class="text-center">Expense Day</th>
                                                            <th class="text-center">Available Day</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="leave in details.leaves" :key="leave.id">
                                                            <td>{{ leave.leave.name  }}</td>
                                                            <td class="text-center">{{ leave.leave.day }}</td>
                                                            <td class="text-center">{{ leave.expense_day }}</td>
                                                            <td class="text-center">{{ leave.leave.day - leave.expense_day }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-end">status</td>
                                            <td>
                                                <span v-if="details.status" class="text-success fw-bold">Active</span>
                                                <span v-else class="text-danger fw-bold">Inactive</span>
                                            </td>
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

            <div class="modal fade" id="viewSalaryHistory" tabindex="-1" role="dialog" aria-labelledby="viewSalaryHistoryTitle" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-centered modal-dialog-scrollable"
                     role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewSalaryHistoryTitle">Employee Salary History</h5>
                            <div><strong>Total: {{ limitAfterDecimal(salaryTotalAmount, 2) }} {{ general.currency_symbol }}</strong></div>
                        </div>
                        <div class="modal-body bg-light">

                            <div v-if="Object.keys(salaryHistory).length" class="table-responsive ">
                                <div class="salaries">
                                    <div v-for="(salary, i) in salaryHistory" class="salary mb-4" :key="salary.key">
                                        <h6>{{ i }}</h6>
                                        <div class="months d-flex gap-2">
                                            <div v-if="salary.length" v-for="month in salary" class="month bg-white shadow-sm p-2 rounded d-flex flex-column align-items-center" >
                                                <span :class="[month.status ? 'text-success' : 'text-danger']"><i class="fa" :class="[month.status ? 'fa-check' : 'fa-times']"></i></span>
                                                <span>{{ getMonthName(month.month) }}</span>
                                                <small>{{ limitAfterDecimal(month.amount, 2) }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary ml-1" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="addEmployee" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addEmployeeTitle" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addEmployeeTitle">Add New Employee</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i data-feather="x"></i></button>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="saveEmployee">
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="firstName">First Name</label>
                                        <input v-model="form.firstName" type="text" class="form-control" id="firstName">
                                        <span class="text-danger" v-if="form.errors.has('firstName')" v-html="form.errors.get('firstName')"></span>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="lastName">Last Name</label>
                                        <input v-model="form.lastName" type="text" class="form-control" id="lastName">
                                        <span class="text-danger" v-if="form.errors.has('lastName')" v-html="form.errors.get('lastName')"></span>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="email">Email</label>
                                        <input v-model="form.email" type="email" class="form-control" id="email">
                                        <span class="text-danger" v-if="form.errors.has('email')" v-html="form.errors.get('email')"></span>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="phone">phone</label>
                                        <input v-model="form.phone" type="text" class="form-control" id="phone">
                                        <span class="text-danger" v-if="form.errors.has('phone')" v-html="form.errors.get('phone')"></span>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label for="address">Address</label>
                                        <textarea v-model="form.address" type="text" class="form-control" id="address"></textarea>
                                        <span class="text-danger" v-if="form.errors.has('address')" v-html="form.errors.get('address')"></span>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="state">State</label>
                                        <input v-model="form.state" type="text" class="form-control" id="state">
                                        <span class="text-danger" v-if="form.errors.has('state')" v-html="form.errors.get('state')"></span>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="zipCode">Zip Code</label>
                                        <input v-model="form.zipCode" type="text" class="form-control" id="zipCode">
                                        <span class="text-danger" v-if="form.errors.has('zipCode')" v-html="form.errors.get('zipCode')"></span>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="salary">Salary</label>
                                        <select v-model="form.salary" class="form-select" id="salary">
                                            <option value="">--Salary--</option>
                                            <option v-if="salaries.length" v-for="salary in salaries" :key="salary.id" :value="salary.id">{{ salary.name }} - {{ salary.amount }}</option>
                                        </select>
                                        <span class="text-danger" v-if="form.errors.has('salary')" v-html="form.errors.get('salary')"></span>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="country">Country</label>
                                        <select v-model="form.country" class="form-select" id="country">
                                            <option value="">--Country--</option>
                                            <option v-if="countries.length" v-for="country in countries" :key="country.id" :value="country.id">{{ country.printable_name }} </option>
                                        </select>
                                        <span class="text-danger" v-if="form.errors.has('country')" v-html="form.errors.get('country')"></span>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="department">Department</label>
                                        <select v-model="form.department" class="form-select" id="department">
                                            <option value="">--Department--</option>
                                            <option v-if="departments.length" v-for="department in departments" :key="department.id" :value="department.id">{{ department.name }} </option>
                                        </select>
                                        <span class="text-danger" v-if="form.errors.has('department')" v-html="form.errors.get('department')"></span>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="designation">Designation</label>
                                        <select v-model="form.designation" class="form-select" id="designation">
                                            <option value="">--Designation--</option>
                                            <option v-if="designations.length" v-for="designation in designations" :key="designation.id" :value="designation.id">{{ designation.name }} </option>
                                        </select>
                                        <span class="text-danger" v-if="form.errors.has('designation')" v-html="form.errors.get('designation')"></span>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label for="leave">Leave</label>
                                        <div id="leave">
                                            <div v-if="leaves.length" v-for="leave in leaves" :key="leave.id" class="form-check form-check-inline">
                                                <input v-model="form.leaves" class="form-check-input" type="checkbox" :id="`leave-${leave.id}`" :value="leave.id">
                                                <label class="form-check-label" :for="`leave-${leave.id}`">{{ leave.name }} ({{ leave.day }} {{ leave.day > 1 ? 'days' : 'day' }})</label>
                                            </div>
                                        </div>
                                        <span class="text-danger" v-if="form.errors.has('leaves')" v-html="form.errors.get('leaves')"></span>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label for="employeeId">Employee ID</label>
                                        <input v-model="form.employeeId" type="text" class="form-control" id="employeeId">
                                        <span class="text-danger" v-if="form.errors.has('employeeId')" v-html="form.errors.get('employeeId')"></span>
                                    </div>
                                </div>


                                <div class="submit text-end mt-5">
                                    <button v-if="form.progress !== undefined" type="button" class="btn btn-primary">
                                        <div class="d-flex align-items-center">
                                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                            <span>Adding...</span>
                                        </div>
                                    </button>
                                    <button v-else type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary ml-1" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="editEmployee" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editEmployeeTitle" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editEmployeeTitle">Edit Employee</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i data-feather="x"></i></button>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="updateEmployee">
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="edit-firstName">First Name</label>
                                        <input v-model="editForm.firstName" type="text" class="form-control" id="edit-firstName">
                                        <span class="text-danger" v-if="editForm.errors.has('firstName')" v-html="editForm.errors.get('firstName')"></span>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="edit-lastName">Last Name</label>
                                        <input v-model="editForm.lastName" type="text" class="form-control" id="edit-lastName">
                                        <span class="text-danger" v-if="editForm.errors.has('lastName')" v-html="editForm.errors.get('lastName')"></span>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="edit-email">Email</label>
                                        <input v-model="editForm.email" type="email" class="form-control" id="edit-email">
                                        <span class="text-danger" v-if="editForm.errors.has('email')" v-html="editForm.errors.get('email')"></span>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="edit-phone">phone</label>
                                        <input v-model="editForm.phone" type="text" class="form-control" id="edit-phone">
                                        <span class="text-danger" v-if="editForm.errors.has('phone')" v-html="editForm.errors.get('phone')"></span>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label for="edit-address">Address</label>
                                        <textarea v-model="editForm.address" type="text" class="form-control" id="edit-address"></textarea>
                                        <span class="text-danger" v-if="editForm.errors.has('address')" v-html="editForm.errors.get('address')"></span>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="edit-state">State</label>
                                        <input v-model="editForm.state" type="text" class="form-control" id="edit-state">
                                        <span class="text-danger" v-if="editForm.errors.has('state')" v-html="editForm.errors.get('state')"></span>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="edit-zipCode">Zip Code</label>
                                        <input v-model="editForm.zipCode" type="text" class="form-control" id="edit-zipCode">
                                        <span class="text-danger" v-if="editForm.errors.has('zipCode')" v-html="editForm.errors.get('zipCode')"></span>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="edit-salary">Salary</label>
                                        <select v-model="editForm.salary" class="form-select" id="edit-salary">
                                            <option value="">--Salary--</option>
                                            <option v-if="salaries.length" v-for="salary in salaries" :key="salary.id" :value="salary.id">{{ salary.name }} - {{ salary.amount }}</option>
                                        </select>
                                        <span class="text-danger" v-if="editForm.errors.has('salary')" v-html="editForm.errors.get('salary')"></span>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="edit-country">Country</label>
                                        <select v-model="editForm.country" class="form-select" id="edit-country">
                                            <option value="">--Country--</option>
                                            <option v-if="countries.length" v-for="country in countries" :key="country.id" :value="country.id">{{ country.printable_name }} </option>
                                        </select>
                                        <span class="text-danger" v-if="editForm.errors.has('country')" v-html="editForm.errors.get('country')"></span>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="edit-department">Department</label>
                                        <select v-model="editForm.department" class="form-select" id="edit-department">
                                            <option value="">--Department--</option>
                                            <option v-if="departments.length" v-for="department in departments" :key="department.id" :value="department.id">{{ department.name }} </option>
                                        </select>
                                        <span class="text-danger" v-if="editForm.errors.has('department')" v-html="editForm.errors.get('department')"></span>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="edit-designation">Designation</label>
                                        <select v-model="editForm.designation" class="form-select" id="edit-designation">
                                            <option value="">--Designation--</option>
                                            <option v-if="designations.length" v-for="designation in designations" :key="designation.id" :value="designation.id">{{ designation.name }} </option>
                                        </select>
                                        <span class="text-danger" v-if="editForm.errors.has('designation')" v-html="editForm.errors.get('designation')"></span>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label for="edit-leave">Leave</label>
                                        <div id="edit-leave">
                                            <div v-if="leaves.length" v-for="leave in leaves" :key="leave.id" class="form-check form-check-inline">
                                                <input v-model="editForm.leaves" class="form-check-input" type="checkbox" :id="`leave-${leave.id}`" :value="leave.id">
                                                <label class="form-check-label" :for="`leave-${leave.id}`">{{ leave.name }} ({{ leave.day }} {{ leave.day > 1 ? 'days' : 'day' }})</label>
                                            </div>
                                        </div>
                                        <span class="text-danger" v-if="editForm.errors.has('leaves')" v-html="editForm.errors.get('leaves')"></span>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label for="edit-employeeId">Employee ID</label>
                                        <input v-model="editForm.employeeId" type="text" class="form-control" id="edit-employeeId">
                                        <span class="text-danger" v-if="editForm.errors.has('employeeId')" v-html="editForm.errors.get('employeeId')"></span>
                                    </div>
                                </div>


                                <div class="submit text-end mt-5">
                                    <button v-if="editForm.progress !== undefined" type="button" class="btn btn-primary">
                                        <div class="d-flex align-items-center">
                                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                            <span>Updating...</span>
                                        </div>
                                    </button>
                                    <button v-else type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary ml-1" data-bs-dismiss="modal" @click="clearEditForm">Close</button>
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
    name: "employees",
    components: { Pagination, Datepicker },
    data() {
        return {
            employees: [],
            details: {},
            salaryHistory: [],
            salaryTotalAmount: 0,
            searchDate: '',
            searchError: '',

            search: {
                id: '',
                email: '',
                phone: '',
                name: '',
                department: '',
                designation: ''
            },

            form: new Form( {
                employeeId: '',
                firstName: '',
                lastName: '',
                email: '',
                phone: '',
                address: '',
                state: '',
                zipCode: '',
                salary: '',
                country: '',
                department: '',
                designation: '',
                leaves: [],
            }),

            editForm: new Form( {
                employeeId: '',
                firstName: '',
                lastName: '',
                email: '',
                phone: '',
                address: '',
                state: '',
                zipCode: '',
                salary: '',
                country: '',
                department: '',
                designation: '',
                leaves: [],
            }),
            editEmployeeId: '',

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
        this.getSalaries();
        this.getCountries();
        this.getLeaves();
    },

    methods: {
        getEmployees(page = 1) {

            const search = this.search;

            this.startLoading();
            axios.get(`employees?page=${ page }&id=${search.id}&email=${search.email}&phone=${search.phone}&department=${search.department}&designation=${search.designation}`)
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

        getSalaries() {
            axios.get('salaries/all').then(res => {
                if (res.data.status) {
                    this.salaries = res.data.salaries;
                }
            }).catch(err => {
                console.log(err);
                Toast.fire('Error', 'Something went wrong!', 'error');
            })
        },

        getCountries() {
            axios.get('general/countries').then(res => {
                if (res.data.status) {
                    this.countries = res.data.countries;
                }
            }).catch(err => {
                console.log(err);
                Toast.fire('Error', 'Something went wrong!', 'error');
            })
        },

        getLeaves() {
            axios.get('leaves/all').then(res => {
                if (res.data.status) {
                    this.leaves = res.data.leaves;
                }
            }).catch(err => {
                console.log(err);
                Toast.fire('Error', 'Something went wrong!', 'error');
            })
        },

        searchEmployee() {
            let page = this.employees.current_page;
            this.getEmployees(page);
        },

        viewEmployee(employee) {
            this.details = employee;
            let myModal = new bootstrap.Modal(document.getElementById('viewEmployee'), {
                keyboard: false,
                //backdrop: true
                //focus: false
            })
            myModal.toggle()
        },

        viewSalaryHistory(employee) {
            this.salaryHistory = employee.salaries_history;
            this.salaryTotalAmount = employee.total_amount;
            let myModal = new bootstrap.Modal(document.getElementById('viewSalaryHistory'), {
                keyboard: false,
                //backdrop: true
                //focus: false
            })
            myModal.toggle()
        },

        addEmployee() {
            let myModal = new bootstrap.Modal(document.getElementById('addEmployee'), {
                keyboard: false,
                backdrop: 'static',
                focus: false
            })

            console.log(myModal)
            myModal.toggle()
        },

        async saveEmployee() {
            await this.form.post('employees/save').then(res => {
                if (res.data.status){
                    this.employees.data.unshift(res.data.employee);
                    Toast.fire('Success', res.data.message, 'success');
                    this.clearForm();
                }
            }).catch(err =>{
                console.log(err);
                Toast.fire('Error', 'Something went wrong!', 'error');
            })
        },

        editEmployee(employee) {
            this.editEmployeeId = employee.id;
            this.editForm.id = employee.id;
            this.editForm.employeeId = employee.emp_id;
            this.editForm.firstName = employee.fname;
            this.editForm.lastName = employee.lname;
            this.editForm.email = employee.email;
            this.editForm.phone = employee.phone;
            this.editForm.address = employee.address;
            this.editForm.state = employee.state;
            this.editForm.zipCode = employee.zip_code;
            this.editForm.salary = employee.salary_id;
            this.editForm.country = employee.country_id;
            this.editForm.department = employee.department_id;
            this.editForm.designation = employee.designation_id;

            let leaves = [];
            employee.leaves.map(leave => {
                leaves.push(leave.leave_id);
            })
            this.editForm.leaves = leaves;

            this.salaryHistory = employee.salaries;
            let myModal = new bootstrap.Modal(document.getElementById('editEmployee'), {
                keyboard: false,
                //backdrop: true
                //focus: false
            })
            myModal.toggle()
        },

        async updateEmployee() {
            await this.editForm.post('employees/update').then(res => {
                if (res.data.status){
                    const employees = this.employees.data;
                    const index = employees.findIndex(employee => employee.id === res.data.employee.id);
                    employees[index] = res.data.employee;
                    Toast.fire('Success', res.data.message, 'success');
                }
            }).catch(err =>{
                console.log(err);
                Toast.fire('Error', 'Something went wrong!', 'error');
            })
        },

        deleteEmployee(id) {
            this.confirm(() => {
                axios.post(`employees/delete`, {id: id})
                    .then(res => {
                        if (res.data.status){
                            this.employees.data = this.employees.data.filter(employee => employee.id !== id);
                            Toast.fire('Success', res.data.message, 'success');
                        }
                    })
                    .catch(err => {
                        this.error(err);
                        Toast.fire('Error', 'Something went wrong!', 'error');
                    });
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
