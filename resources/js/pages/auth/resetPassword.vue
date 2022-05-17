<template>
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-12">
                <div id="auth-right">
                    <div id="auth-box" class="mx-2">
                        <div class="auth-logo mb-4 text-center">
                            <h3 class="m-0">BMS</h3>
                            <h6>Bakery Management System</h6>
                        </div>
                        <div class="auth-logo mb-4 text-center">
                            <h5 class="m-0">Reset Password</h5>
                        </div>
                        <form v-if="codeVerified" @submit.prevent="resetPassword">
                            <div class="mb-3">
                                <div class="form-group position-relative has-icon-left mb-2">
                                    <input type="password" class="form-control form-control-md" placeholder="Password" v-model="form.password">
                                    <div class="form-control-icon"><i class="bi bi-person"></i></div>
                                </div>
                                <span class="text-danger" v-if="form.errors.has('password')" v-html="form.errors.get('password')"></span>
                            </div>
                            <div class="mb-3">
                                <div class="form-group position-relative has-icon-left mb-2">
                                    <input type="password" class="form-control form-control-md" placeholder="Confirm Password" v-model="form.confirmPassword">
                                    <div class="form-control-icon"><i class="bi bi-person"></i></div>
                                </div>
                                <span class="text-danger" v-if="form.errors.has('confirmPassword')" v-html="form.errors.get('confirmPassword')"></span>
                            </div>
                            <div>
                                <button v-if="form.progress !== undefined" type="button" class="btn btn-primary btn-block btn-md shadow-lg">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                        <span>Resetting...</span>
                                    </div>
                                </button>
                                <button v-else type="submit" class="btn btn-primary btn-block btn-md shadow-lg">Reset Password</button>
                            </div>
                        </form>
                        <div v-else class="text-center">
                            <h5>Checking...</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: "resetPassword",
    data() {
        return {
            form: new Form({
                code: this.$route.params.code,
                email: this.$route.params.email,
                password: '',
                confirmPassword: '',
            }),
            codeVerified: false
        }
    },

    mounted() {
        this.verifyCode();
    },

    methods: {

        verifyCode() {
            axios.post('auth/forgot-password/verify-code', {email: this.$route.params.email, code: this.$route.params.code,})
                .then(res => {
                    if (res.data.status){
                        this.codeVerified = true;
                    }else{
                        this.$router.push({ name: 'forgotPassword' });
                    }
                })
                .catch(err => {
                    console.log(err);
                    Toast.fire('Error', 'Something went wrong!', 'error');
                    this.$router.push({ name: 'forgotPassword' });
                })
        },

        resetPassword() {
            this.form.post('auth/reset-password')
                .then(res => {
                    if (res.data.status){
                        Toast.fire('Success', res.data.message, 'success');
                        this.$router.push({ name: 'login' });
                    }else{
                        Toast.fire('Error', res.data.message, 'error');
                    }
                })
                .catch(err => {
                    console.log(err);
                    Toast.fire('Error', 'Something went wrong!', 'error');
                })
        }
    }
}
</script>
