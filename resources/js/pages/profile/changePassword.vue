<template>
    <div>
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h3>Change Password</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content">
            <div class="row justify-content-center" id="table-bordered">
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-content p-4">
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <form @submit.prevent="updatePassword">
                                        <div class="form-group">
                                            <label for="oldPassword">Old Password</label>
                                            <input type="password" id="oldPassword" v-model="form.oldPassword" class="form-control">
                                            <span class="text-danger" v-if="form.errors.has('oldPassword')" v-html="form.errors.get('oldPassword')"></span>
                                        </div>
                                        <div class="form-group">
                                            <label for="newPassword">New Password</label>
                                            <input type="password" id="newPassword" v-model="form.newPassword" class="form-control">
                                            <span class="text-danger" v-if="form.errors.has('newPassword')" v-html="form.errors.get('newPassword')"></span>
                                        </div>
                                        <div class="form-group">
                                            <label for="confirmPassword">Confirm Password</label>
                                            <input type="password" id="confirmPassword" v-model="form.confirmPassword" class="form-control">
                                            <span class="text-danger" v-if="form.errors.has('confirmPassword')" v-html="form.errors.get('confirmPassword')"></span>
                                        </div>

                                        <div class="text-end">
                                            <button v-if="form.progress !== undefined" type="button" class="btn btn-primary">
                                                <div class="d-flex align-items-center">
                                                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                                    <span>Updating...</span>
                                                </div>
                                            </button>
                                            <button v-else type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "changePassword",
    data() {
        return {
            form: new Form({
                oldPassword: '',
                newPassword: '',
                confirmPassword: '',
            }),
        }
    },

    methods: {
        updatePassword() {
            this.form.post('user/update-password')
                .then(res => {
                    if (res.data.status){
                        Toast.fire('Success', res.data.message, 'success');
                        this.cleanForm()
                    }
                }).catch(err => {
                console.log(err);
                Toast.fire('Error', 'Something went wrong!', 'error');
            })
        },

        cleanForm() {
            this.form.oldPassword = '';
            this.form.newPassword = '';
            this.form.confirmPassword = '';
        }

    },

}
</script>

<style scoped>

</style>
