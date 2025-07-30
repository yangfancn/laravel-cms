<template>
  <q-layout view="lHh lpR fFf">
    <q-page-container>
      <q-page class="login-page">
        <Head :title="title" />
        <q-card>
          <h1 class="text-center">{{ title }}</h1>
          <Form :fields="form.fields" :action="form.action" :method="form.method" :data="form.data">
            <template v-slot:submit>
              <div class="col-12 text-center">
                <q-btn type="submit" color="primary" square>{{ $t("submit") }}</q-btn>
              </div>
            </template>
          </Form>
        </q-card>
      </q-page>
    </q-page-container>

    <q-ajax-bar ref="bar" position="top" color="accent" size="10px" skip-hijack />
  </q-layout>
</template>

<script setup lang="ts">
import { Head } from "@inertiajs/vue3"
import Form from "../Components/Form/Form.vue"
import { useNotify } from "../../composables/useNotify"

defineProps<{
  form: any
  title: string
}>()

useNotify()
</script>

<style lang="scss" scoped>
.login-page {
  display: flex;
  justify-content: center;
  align-items: center;

  :deep(.q-card) {
    max-width: 420px;
    padding: 32px;

    h1 {
      font-size: 1.65rem;
      line-height: 1;
      margin-bottom: 2rem;
      font-weight: bold;
      text-transform: uppercase;
      color: #afafaf;
    }

    .q-toggle {
      margin-left: -0.75rem;
    }
  }
}
</style>
