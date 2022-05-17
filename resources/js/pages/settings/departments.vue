<template>
    <div>
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Departments</h3>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><router-link :to="{ name: 'dashboard' }">Dashboard</router-link></li>
                                <li class="breadcrumb-item active" aria-current="page">Departments</li>
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
                                    <h4 class="card-title">All Departments List</h4>
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
                                    <div v-if="departments.data && departments.data.length" >
                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-0">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th>Name</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                    <tr v-for="(department, i) in departments.data">
                                                        <td class="text-center">{{ i+departments.from }}</td>
                                                        <td>{{ department.name }}</td>
                                                        <td class="text-center">
                                                            <button class="btn btn-light dropdown-toggle btn-sm" type="button" id="actions" data-bs-toggle="dropdown" aria-expanded="false">
                                                                Action
                                                            </button>
                                                            <ul class="dropdown-menu shadow dropdown-menu-actions" aria-labelledby="actions">
                                                                <li><button class="dropdown-item d-flex align-items-center" type="button" @click="edit(department.id)"><i class="bi bi-pencil me-2"></i> Edit</button></li>
                                                                <li><button class="dropdown-item d-flex align-items-center" type="button" @click="deleteItem(department.id)"><i class="bi bi-trash me-2"></i> Delete</button></li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <Pagination v-if="isPaginate(departments)" :links="departments.links" :callBack="getDepartments" />

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
                                    <h4 v-if="isEdit" class="card-title">Update Department</h4>
                                    <h4 v-else class="card-title">Add New Department</h4>
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
    name: "departments",
    components: { Pagination },
    data() {
        return {
            form: new Form({
                name: ''
            }),
            isEdit: false,
            editId: '',
            departments: []
        }
    },

    mounted() {
        this.getDepartments()
    },

    methods: {
        getDepartments(page = 1) {
            this.startLoading();
            axios.get(`departments?page=${ page }`)
                .then(res => {
                    if (res.data.status) {
                        this.departments = res.data.departments;
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
            this.form.post('departments/save')
                .then(res => {
                    if (res.data.status) {
                        this.departments.data.unshift(res.data.department);
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
            let data = this.departments.data.filter(department => department.id === id);
            this.form.name = data[0].name;
            this.form.id = id;
            this.isEdit = true;
            this.editId = id;
        },

        update(e) {
            e.preventDefault();
            this.form.post(`departments/update`)
                .then(res => {
                    if (res.data.status) {
                        let updatedDepartment = res.data.department;
                        let departments = this.departments.data;

                        //Find index of specific object using findIndex method.
                        const objIndex = departments.findIndex(( department => department.id === updatedDepartment.id ));
                        departments[objIndex] = updatedDepartment;
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
                axios.post(`departments/delete`, {id: id})
                    .then(res => {
                        if (res.data.status){
                            this.departments.data = this.departments.data.filter(department => department.id !== id);
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
            this.isEdit = false;
            this.editId = '';
        }
    },

}
</script>

<style scoped>

</style>
