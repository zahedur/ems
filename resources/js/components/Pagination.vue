<template>
    <div class="pagination mt-5">
        <nav aria-label="Page navigation" class="w-100">
            <ul class="pagination pagination-primary justify-content-center">
                <li v-for="(link, i) in links" class="page-item" :class="{ active : link.active, disabled: !link.url }">
                    <div v-if="link.url" @click="getPaginateData(link.url)" class="page-link cursor-pointer" v-html="link.label"></div>
                    <div v-else class="page-link cursor-pointer" v-html="link.label"></div>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
export default {
    name: "Pagination",
    props: ['links', 'callBack'],

    methods: {
        getPaginateData(url) {
            this.callBack(this.getPageNumber(url));
        }
    }
}
</script>

<style scoped>

</style>
