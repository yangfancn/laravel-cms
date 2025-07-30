<template>
  <Layout :title="title">
    <Form
      :fields="form.fields"
      :action="form.action"
      :method="form.method"
      :data="form.data"
    ></Form>
  </Layout>
</template>

<script setup lang="ts">
import Layout from "./Layout.vue"
import Form from "./Components/Form/Form.vue"

defineProps<{
  form: any
  title: string
}>()
</script>
