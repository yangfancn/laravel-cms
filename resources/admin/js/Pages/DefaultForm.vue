<template>
  <Layout :title="title">
    <Form
      :fields="form.fields"
      :action="form.action"
      :method="form.method"
      :data="form.data"
      :precognitive="true"
    ></Form>
  </Layout>
</template>

<script setup lang="ts">
import Layout from "./Layout.vue"
import Form from "./Components/Form/Form.vue"
import { type FormProps } from "../types/propTypes"

defineProps<{
  form: FormProps
  title: string
}>()
</script>
