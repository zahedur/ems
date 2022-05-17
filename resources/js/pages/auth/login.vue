<template>
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-12">
                <div id="authentication">
                    <div v-if="general.logo" class="logo mb-3">
                        <img :src="general.logo" alt="Logo" class="img-fluid">
                    </div>
                    <div id="auth-box" class="mx-2">
                        <div class="auth-logo mb-4 text-center">
                            <h3 class="m-0">{{ general.appName }}</h3>
                            <h6>{{ general.title }}</h6>
                        </div>
                        <form @submit.prevent="login">
                            <div class="mb-2">
                                <div class="form-group position-relative has-icon-left mb-0">
                                    <input type="email" class="form-control form-control-md" placeholder="Email" v-model="form.email">
                                    <div class="form-control-icon"><i class="bi bi-person"></i></div>
                                </div>
                                <span class="text-danger" v-if="form.errors.has('email')" v-html="form.errors.get('email')"></span>
                            </div>

                            <div class="mb-2">
                                <div class="form-group position-relative has-icon-left mb-0">
                                    <input type="password" class="form-control form-control-md" placeholder="Password" v-model="form.password">
                                    <div class="form-control-icon"><i class="bi bi-shield-lock"></i></div>
                                </div>
                                <span class="text-danger" v-if="form.errors.has('password')" v-html="form.errors.get('password')"></span>
                            </div>
                            <div class="text-end mb-4">
                                <p><router-link :to="{name: 'forgotPassword'}" class="font-bold fs-6" href="#">Forgot password?</router-link></p>
                            </div>
                            <!--                            <div class="form-check form-check-lg d-flex align-items-end">-->
                            <!--                                <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault">-->
                            <!--                                <label class="form-check-label text-gray-600" for="flexCheckDefault">-->
                            <!--                                    Keep me logged in-->
                            <!--                                </label>-->
                            <!--                            </div>-->
                            <div>
                                <button v-if="form.progress !== undefined" type="button" class="btn btn-primary btn-block btn-md shadow-lg">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                        <span>Login...</span>
                                    </div>
                                </button>
                                <button v-else type="submit" class="btn btn-primary btn-block btn-md shadow-lg">Login</button>
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
    name: "login",
    //middleware: 'auth',
    data() {
        return {
            form: new Form({
                email: 'zahedurr47@gmail.com',
                password: '123456789'
            })
        }
    },

    methods: {
        login() {
            this.startLoading();
            this.form.post('auth/login')
                .then(res => {
                    if (res.data.status){
                        localStorage.setItem('auth', JSON.stringify(res.data));
                        this.$store.commit('auth/setAuthData');
                        //this.$store.dispatch('auth/isAuthenticated');
                        this.$store.dispatch('general/generalData');
                        this.$router.push({ name: 'dashboard' });
                        this.endLoading();
                        if (window.innerWidth > 1199){
                            this.$store.commit('helper/sidebar', true);
                        }
                    }else{
                        Toast.fire('Error', res.data.message, 'error');
                    }

                })
                .catch(err => {
                    console.log(err);
                    if (err.response) {
                        Toast.fire('Error', err.response.data.message, 'error');
                    }
                })
        }
    }
}
</script>
