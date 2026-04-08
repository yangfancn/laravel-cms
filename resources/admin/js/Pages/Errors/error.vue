<template>
  <Layout>
    <Head :title="message" />
    <v-empty-state :headline="status" :title="message" color="error" icon="mdi-alert"></v-empty-state>
  </Layout>
</template>

<script setup lang="ts">
import Layout from "../Layout.vue"
import { Head } from "@inertiajs/vue3"

defineProps<{
  message: string
  status: number
}>()
</script>
