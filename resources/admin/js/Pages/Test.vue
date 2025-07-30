<template>
  <Layout title="TestPage">
    <Input model-value="aaa" />
  </Layout>
</template>

<script setup lang="ts">
import Layout from "./Layout.vue"
import { onMounted, ref } from "vue"
import Input from "./Components/Form/Fields/Input.vue"

const props = defineProps<{}>()

const po = ref(null)

onMounted(() => {})
</script>

<style lang="scss"></style>
