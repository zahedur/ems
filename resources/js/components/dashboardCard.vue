<template>
    <div class="card m-0 mb-3">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="text-muted font-semibold">{{ title }}</h6>
                    <h6 class="font-extrabold mb-0">{{ value }}</h6>
                </div>
                <div class="stats-icon" v-html="icon"></div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "dashboardCard",
    props: ['title', 'value', 'icon']
}
</script>

<style scoped>

</style>
