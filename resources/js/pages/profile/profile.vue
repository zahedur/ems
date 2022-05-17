<template>
    <div>
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h3>Profile</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content">
            <div class="row justify-content-center" id="table-bordered">
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-content p-4">
                            <div v-if="loading" class="d-flex justify-content-center align-items-center">
                                <div class="loading">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <div class="row justify-content-center">
                                    <div class="col-md-6">
                                        <form @submit.prevent="updateUser">

                                            <div class="profile-photo">
                                                <img v-if="photoPreview" :src="photoPreview" alt="Logo" class="img-fluid profile-photo-image">
                                                <img v-else-if="oldPhoto" :src="oldPhoto" alt="Logo" class="img-fluid profile-photo-image">
                                                <div v-else class="profile-photo-image" style="background: #435ebe;">
                                                    <span>{{ getFirstLetter(user.name) }}</span>
                                                </div>
                                                <label type="button" class="upload-photo" for="profilePhotoInputFile"><i class="bi bi-camera"></i></label>
                                            </div>
                                            <div class="form-group">
                                                <label for="app_name">Name</label>
                                                <input type="text" id="app_name" v-model="form.name" class="form-control">
                                                <span class="text-danger" v-if="form.errors.has('name')" v-html="form.errors.get('name')"></span>
                                            </div>
                                            <div class="form-group d-none">
                                                <label for="Photo">Photo</label>
                                                    <div class="input-group mb-3" id="photo">
                                                        <input type="file" @change="fileHandler" class="form-control" id="profilePhotoInputFile">
                                                        <label class="input-group-text" for="profilePhotoInputFile">Upload</label>
                                                    </div>
                                                <span class="text-danger" v-if="form.errors.has('logo')" v-html="form.errors.get('logo')"></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="title">Email</label>
                                                <input type="text" id="title" v-model="email" class="form-control" readonly>
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
    </div>
</template>

<script>
export default {
    name: "general",
    data() {
        return {
            form: new Form({
                name: '',
                photo: '',
            }),
            oldPhoto: '',
            email: '',
            photoPreview: ''
        }
    },

    watch: {
        userData: function (val, oldVal) {
            this.form.name = val.name;
            this.oldPhoto = val.image;
            this.email = val.email;
        }
    },

    mounted() {
        this.setUserData()
    },

    methods: {
        setUserData() {
            this.form.name = this.userData.name;
            this.oldPhoto = this.userData.image;
            this.email = this.userData.email;
        },

        updateUser() {
            this.form.post('user/update')
                .then(res => {
                    if (res.data.status){
                        this.$store.commit('auth/user', res.data.user);
                        let localStorageData = JSON.parse(localStorage.getItem('auth'));
                        localStorageData.user = res.data.user;
                        localStorage.setItem('auth', JSON.stringify(localStorageData));
                        Toast.fire('Success', res.data.message, 'success');
                    }
                }).catch(err => {
                    console.log(err);
                    Toast.fire('Error', 'Something went wrong!', 'error');
                })
        },

        fileHandler(e) {
            const file = e.target.files[0];
            this.photoPreview = URL.createObjectURL(file);
            this.form.photo = file;
        }
    },

    computed: {
        userData() {
            let user = this.$store.getters["auth/user"];
            return {
                name: user.name,
                email: user.email,
                image: user.image,
            };
        }
    }
}
</script>

<style scoped>

</style>
