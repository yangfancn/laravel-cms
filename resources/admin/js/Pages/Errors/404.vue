<template>
  <Layout>
    <Head title="404-NotFound" />
  </Layout>
</template>

<script setup lang="ts">
import Layout from "../Layout.vue"
import { Head } from "@inertiajs/vue3"

const props = defineProps<{}>()

console.log(props)
</script>
