<template>
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-12">
                <div id="auth-right">
                    <div v-if="codeSent" id="auth-box" class="mx-2">
                        <div class="auth-logo mb-4 text-center">
                            <h3 class="m-0">BMS</h3>
                            <h6>Bakery Management System</h6>
                        </div>
                        <div class="auth-logo mb-4 text-center">
                            <h5 class="m-0">Verify Code</h5>
                        </div>
                        <form @submit.prevent="verifyCode">
                            <div class="mb-3">
                                <div class="form-group position-relative has-icon-left mb-2">
                                    <input type="text" class="form-control form-control-md" placeholder="Code" v-model="formCodeVerify.code">
                                    <div class="form-control-icon"><i class="bi bi-person"></i></div>
                                </div>
                                <span class="text-danger" v-if="form.errors.has('code')" v-html="form.errors.get('code')"></span>
                            </div>
                            <div>
                                <button v-if="formCodeVerify.progress !== undefined" type="button" class="btn btn-primary btn-block btn-md shadow-lg">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                        <span>Verifying...</span>
                                    </div>
                                </button>
                                <button v-else type="submit" class="btn btn-primary btn-block btn-md shadow-lg">Verify</button>
                            </div>
                        </form>
                    </div>
                    <div v-else id="auth-box" class="mx-2">
                        <div class="auth-logo mb-4 text-center">
                            <h3 class="m-0">BMS</h3>
                            <h6>Bakery Management System</h6>
                        </div>
                        <div class="auth-logo mb-4 text-center">
                            <h5 class="m-0">Forgot Password</h5>
                        </div>
                        <form @submit.prevent="forgotPassword">
                            <div class="mb-3">
                                <div class="form-group position-relative has-icon-left mb-2">
                                    <input type="email" class="form-control form-control-md" placeholder="Email" v-model="form.email">
                                    <div class="form-control-icon"><i class="bi bi-person"></i></div>
                                </div>
                                <span class="text-danger" v-if="form.errors.has('email')" v-html="form.errors.get('email')"></span>
                            </div>
                            <div>
                                <button v-if="form.progress !== undefined" type="button" class="btn btn-primary btn-block btn-md shadow-lg">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                        <span>Sending...</span>
                                    </div>
                                </button>
                                <button v-else type="submit" class="btn btn-primary btn-block btn-md shadow-lg">Send Code</button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: "forgotPassword",
    data() {
        return {
            form: new Form({
                email: '',
            }),
            formCodeVerify: new Form({
                email: '',
                code: '',
            }),
            codeSent: false,
        }
    },

    methods: {
        forgotPassword() {
            this.form.post('auth/forgot-password')
                .then(res => {
                    if (res.data.status){
                        this.codeSent = true;
                        this.formCodeVerify.email = this.form.email;
                        Toast.fire('Success', res.data.message, 'success');
                    }
                })
                .catch(err => {
                    console.log(err);
                    Toast.fire('Error', 'Something went wrong!', 'error');
                })
        },

        verifyCode() {
            this.formCodeVerify.post('auth/forgot-password/verify-code')
                .then(res => {
                    if (res.data.status){
                        Toast.fire('Success', res.data.message, 'success');
                        this.$router.push({ name: 'resetPassword', params: { code: this.formCodeVerify.code, email: this.form.email } });
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
