<template>
    <div>
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Settings</h3>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><router-link :to="{ name: 'dashboard' }">Dashboard</router-link></li>
                                <li class="breadcrumb-item active" aria-current="page">Settings</li>
                                <li class="breadcrumb-item active" aria-current="page">General</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content">
            <div class="row justify-content-center" id="table-bordered">
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header py-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="card-title">General</h4>
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
                                <form @submit.prevent="updateGeneral">
                                    <div class="form-group">
                                        <label for="app_name">App Name</label>
                                        <input type="text" id="app_name" v-model="form.name" class="form-control">
                                        <span class="text-danger" v-if="form.errors.has('name')" v-html="form.errors.get('name')"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="logo">Logo</label>
                                        <div class="row" id="logo">
                                            <div class="col-md-8">
                                                <div class="input-group mb-3">
                                                    <input type="file" @change="fileHandler" class="form-control" id="inputGroupFile02">
                                                    <label class="input-group-text" for="inputGroupFile02">Upload</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <img v-if="logoPreview" :src="logoPreview" alt="Logo" class="img-fluid">
                                                <img v-else :src="oldLogo" alt="Logo" class="img-fluid">
                                            </div>
                                        </div>
                                        <span class="text-danger" v-if="form.errors.has('logo')" v-html="form.errors.get('logo')"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" id="title" v-model="form.title" class="form-control">
                                        <span class="text-danger" v-if="form.errors.has('title')" v-html="form.errors.get('title')"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea id="description" v-model="form.description" class="form-control"></textarea>
                                        <span class="text-danger" v-if="form.errors.has('description')" v-html="form.errors.get('description')"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="currency_text">Currency Text</label>
                                        <input type="text" id="currency_text" v-model="form.currency_text" class="form-control">
                                        <span class="text-danger" v-if="form.errors.has('currency_text')" v-html="form.errors.get('currency_text')"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="currency_symbol">Currency Symbol</label>
                                        <input type="text" id="currency_symbol" v-model="form.currency_symbol" class="form-control">
                                        <span class="text-danger" v-if="form.errors.has('currency_symbol')" v-html="form.errors.get('currency_symbol')"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="version">Version</label>
                                        <input type="text" id="version" v-model="form.version" class="form-control">
                                        <span class="text-danger" v-if="form.errors.has('version')" v-html="form.errors.get('version')"></span>
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
</template>

<script>
export default {
    name: "general",
    data() {
        return {
            form: new Form({
                name: '',
                logo: '',
                title: '',
                description: '',
                currency_text: '',
                currency_symbol: '',
                version: ''
            }),
            oldLogo: '',
            logoPreview: ''
        }
    },

    watch: {
        generalData: function (val, oldVal) {
            this.form.name = val.name;
            this.form.title = val.title;
            this.form.description = val.description;
            this.form.currency_text = val.currency_text;
            this.form.currency_symbol = val.currency_symbol;
            this.form.version = val.version;
            this.oldLogo = val.logo;
        }
    },

    mounted() {
        this.setGeneralData()
    },

    methods: {
        setGeneralData() {
            this.form.name = this.generalData.name;
            this.form.title = this.generalData.title;
            this.form.description = this.generalData.description;
            this.form.currency_text = this.generalData.currency_text;
            this.form.currency_symbol = this.generalData.currency_symbol;
            this.form.version = this.generalData.version;
            this.oldLogo = this.generalData.logo;
        },

        updateGeneral() {
            this.form.post('general/update')
                .then(res => {
                    if (res.data.status){
                        this.$store.commit('general/general', res.data.general);
                        Toast.fire('Success', res.data.message, 'success');
                    }
                }).catch(err => {
                    console.log(err);
                    Toast.fire('Error', 'Something went wrong!', 'error');
                })
        },

        fileHandler(e) {
            const file = e.target.files[0];
            this.logoPreview = URL.createObjectURL(file);
            this.form.logo = file;
        }
    },

    computed: {
        generalData() {
            let general = this.$store.getters["general/general"];
            return {
                name: general.appName,
                title: general.title,
                description: general.description,
                currency_text: general.currency_text,
                currency_symbol: general.currency_symbol,
                version: general.version,
                logo: general.logo
            };
        }
    }
}
</script>

<style scoped>

</style>
