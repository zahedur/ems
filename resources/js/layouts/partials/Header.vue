<template>
    <header>
        <nav class="navbar navbar-expand navbar-light ">
            <div class="container-fluid">
                <div class="d-flex justify-content-between justify-content-xl-end w-100">
                    <div class="cursor-pointer d-xl-none d-flex align-items-center" @click="() => this.$store.commit('helper/sidebar', true)">
                        <i class="bi bi-justify hamburger-icon"></i>
                    </div>
                    <div>
                        <div class="dropdown">
                            <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="user-menu d-flex">
                                    <div class="user-img d-flex align-items-center">
                                        <div class="avatar avatar-md">
                                            <img v-if="user.image" :src="user.image" class="user-name-letter" :alt="getFirstLetter(user.name)">
                                            <div v-else class="user-name-letter" style="background: #435ebe"><span>{{ getFirstLetter(user.name)}}</span></div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownMenuButton">
                                <li>
                                    <div class="user-name py-2 px-4">
                                        <h6 class="mb-0 text-gray-600">{{ user.name }}</h6>
                                        <p class="mb-0 text-sm text-gray-600">{{ user.email }}</p>
                                        <p class="mb-0 mt-2 text-sm text-primary font-bold">{{ user.role }}</p>
                                    </div>
                                </li>
                                <hr class="dropdown-divider">
                                <li>
                                    <router-link :to="{name: 'profile'}" class="dropdown-item"><i class="icon-mid bi bi-person me-2"></i> My Profile</router-link>
                                </li>
                                <li>
                                    <router-link :to="{name: 'changePassword'}" class="dropdown-item"><i class="icon-mid bi bi-key me-2"></i>Change Password</router-link>
                                </li>
                                <hr class="dropdown-divider">
                                <li>
                                    <button @click="logout" class="dropdown-item" href="#">
                                        <i class="icon-mid bi bi-box-arrow-left me-2"></i> Logout
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

</template>

<script>
export default {
    name: "Header",
    data() {
        return {
            sidebar: false
        }
    },

    methods: {
        logout() {
            this.$store.dispatch('auth/AttemptToLogout');
        },
    }

    // document.querySelector('.burger-btn').addEventListener('click', () => {
    //     document.getElementById('sidebar').classList.toggle('active');
    // })
    // document.querySelector('.sidebar-hide').addEventListener('click', () => {
    //     document.getElementById('sidebar').classList.toggle('active');
    //
    // })
}
</script>

<style scoped>

</style>
