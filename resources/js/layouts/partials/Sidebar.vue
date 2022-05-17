<template>
    <div id="sidebar" :class="this.windowWidth < 1199 ? this.$store.getters['helper/sidebar'] ? 'active' : '' : 'active'">
        <div class="sidebar-wrapper active">
            <div class="sidebar-header">
                <div class="d-flex justify-content-between">
                    <div class="logo logo d-flex flex-column">
                        <router-link v-if="general.logo" to="/"><img :src="general.logo" alt="Logo"></router-link>
                        <h3 v-else>{{ general.appName }}</h3>
                        <small v-if=" general.title" class="app-title">{{ general.title }}</small>
                        <small v-if=" general.version" class="app-version">v{{ general.version }}</small>
                    </div>
                    <div class="toggler">
                        <a href="#" @click="() => this.$store.commit('helper/sidebar', false)" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                    </div>
                </div>
            </div>
            <div class="sidebar-menu">
                <ul class="menu">
                    <li class="sidebar-item">
                        <router-link to="/" class='sidebar-link'>
                            <i class="bi bi-grid-fill"></i>
                            <span>Dashboard</span>
                        </router-link>
                    </li>

                    <li class="sidebar-item ">
                        <router-link :to="{ name: 'employees'}" class='sidebar-link'>
                            <i class="fa fa-users"></i>
                            <span>Employees</span>
                        </router-link>
                    </li>

                    <li class="sidebar-item ">
                        <router-link :to="{ name: 'giveSalary'}" class='sidebar-link'>
                            <i class="fa fa-credit-card"></i>
                            <span>Give Salary</span>
                        </router-link>
                    </li>

                    <li class="sidebar-item  has-sub">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-journal-text"></i>
                            <span>Reports</span>
                        </a>
                        <ul class="submenu">
                            <li class="submenu-item ">
                                <router-link :to="{name: 'reportMonthly'}">Monthly</router-link>
                            </li>
                            <li class="submenu-item ">
                                <router-link :to="{name: 'reportYearly'}">Yearly</router-link>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item  has-sub">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-gear-fill"></i>
                            <span>Settings</span>
                        </a>
                        <ul class="submenu ">
                            <li class="submenu-item ">
                                <router-link :to="{name: 'settingsGeneral'}">General</router-link>
                            </li>
                            <li class="submenu-item ">
                                <router-link :to="{name: 'departments'}">Departments</router-link>
                            </li>
                            <li class="submenu-item ">
                                <router-link :to="{name: 'designations'}">Designations</router-link>
                            </li>
                            <li class="submenu-item ">
                                <router-link :to="{name: 'leaves'}">Leaves</router-link>
                            </li>
                            <li class="submenu-item ">
                                <router-link :to="{name: 'salaries'}">Salaries</router-link>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
            <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
        </div>
    </div>
</template>

<script>

export default {
    name: "Sidebar",
    data(){
        return {
            windowWidth: 0
        }
    },

    mounted() {
        window.addEventListener('load', () => {
            this.windowWidth = window.innerWidth
        })

        window.addEventListener('resize', (event) => {
            this.windowWidth = window.innerWidth
        });

        this.loadCustomJs();
    },

    methods: {
        loadCustomJs() {
            function slideToggle(t,e,o){0===t.clientHeight?j(t,e,o,!0):j(t,e,o)}function slideUp(t,e,o){j(t,e,o)}function slideDown(t,e,o){j(t,e,o,!0)}function j(t,e,o,i){void 0===e&&(e=400),void 0===i&&(i=!1),t.style.overflow="hidden",i&&(t.style.display="block");var p,l=window.getComputedStyle(t),n=parseFloat(l.getPropertyValue("height")),a=parseFloat(l.getPropertyValue("padding-top")),s=parseFloat(l.getPropertyValue("padding-bottom")),r=parseFloat(l.getPropertyValue("margin-top")),d=parseFloat(l.getPropertyValue("margin-bottom")),g=n/e,y=a/e,m=s/e,u=r/e,h=d/e;window.requestAnimationFrame(function l(x){void 0===p&&(p=x);var f=x-p;i?(t.style.height=g*f+"px",t.style.paddingTop=y*f+"px",t.style.paddingBottom=m*f+"px",t.style.marginTop=u*f+"px",t.style.marginBottom=h*f+"px"):(t.style.height=n-g*f+"px",t.style.paddingTop=a-y*f+"px",t.style.paddingBottom=s-m*f+"px",t.style.marginTop=r-u*f+"px",t.style.marginBottom=d-h*f+"px"),f>=e?(t.style.height="",t.style.paddingTop="",t.style.paddingBottom="",t.style.marginTop="",t.style.marginBottom="",t.style.overflow="",i||(t.style.display="none"),"function"==typeof o&&o()):window.requestAnimationFrame(l)})}

            let sidebarItems = document.querySelectorAll('.sidebar-item.has-sub');
            for(var i = 0; i < sidebarItems.length; i++) {
                let sidebarItem = sidebarItems[i];
                sidebarItems[i].querySelector('.sidebar-link').addEventListener('click', function(e) {
                    e.preventDefault();

                    let submenu = sidebarItem.querySelector('.submenu');
                    if( submenu.classList.contains('active') ) submenu.style.display = "block"

                    if( submenu.style.display == "none" ) submenu.classList.add('active')
                    else submenu.classList.remove('active')
                    slideToggle(submenu, 300)
                })
            }

            // window.addEventListener('DOMContentLoaded', (event) => {
            //     var w = window.innerWidth;
            //     if(w < 1200) {
            //         document.getElementById('sidebar').classList.remove('active');
            //     }
            // });
            // window.addEventListener('resize', (event) => {
            //     var w = window.innerWidth;
            //     if(w < 1200) {
            //         document.getElementById('sidebar').classList.remove('active');
            //     }else{
            //         document.getElementById('sidebar').classList.add('active');
            //     }
            // });

            // document.querySelector('.burger-btn').addEventListener('click', () => {
            //     document.getElementById('sidebar').classList.toggle('active');
            // })
            // document.querySelector('.sidebar-hide').addEventListener('click', () => {
            //     document.getElementById('sidebar').classList.toggle('active');
            //
            // })


            // Perfect Scrollbar Init
            if(typeof PerfectScrollbar == 'function') {
                const container = document.querySelector(".sidebar-wrapper");
                const ps = new PerfectScrollbar(container, {
                    wheelPropagation: false
                });
            }

            // Scroll into active sidebar
            let sidebarActive = document.querySelector('.sidebar-item.active');
            if (sidebarActive){
                sidebarActive.scrollIntoView(false);
            }

        }
    },

    computed: {
        general() {
            return this.$store.getters["general/general"];
        }
    }
}
</script>

<style scoped>

</style>
