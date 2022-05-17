<template>
    <div>
        <div v-if="authenticated">
            <Sidebar />
            <div id="main" class='layout-navbar'>
                <Header />
                <div id="main-content">
                    <div id="main-content-body">
                        <router-view></router-view>
                    </div>
                    <Footer />
                </div>
            </div>
        </div>
        <div v-else>
            <router-view></router-view>
        </div>

    </div>
</template>

<script>

import Header from "./partials/Header";
import Sidebar from "./partials/Sidebar";
import Footer from "./partials/Footer";
export default {
    name: "App",
    components: { Header, Sidebar, Footer },

    data() {
        return {

        }
    },

    mounted() {
        this.$store.dispatch('general/generalData');
    },

}
</script>

<style scoped>

</style>
