<template>
  <q-layout view="lHh lpR fFf">
    <q-page-container>
      <q-page class="sign-up-page">
        <Head title="Sign-up" />
        <q-card>
          <h1>Sign-up</h1>
          <Form
            :fields="form.fields"
            :action="form.action"
            :method="form.method"
            :data="form.data"
            :precognitive="false"
          >
            <template v-slot:submit>
              <div class="col-12">
                <q-btn type="submit" color="primary full-width" square>{{ $t("login") }}</q-btn>
              </div>
            </template>
          </Form>
          <p style="margin-top: 1rem">Has account? <a :href="safeRoute('login')">Sign in</a></p>
        </q-card>
      </q-page>
    </q-page-container>

    <q-ajax-bar ref="bar" position="top" color="accent" size="10px" skip-hijack />
  </q-layout>
</template>

<script setup lang="ts">
import { Head } from "@inertiajs/vue3"
import Form from "../Components/Form/Form.vue"
import { safeRoute } from "../../helper"

defineProps<{
  form: any
}>()
</script>

<style scoped>
.sign-up-page {
  display: flex;
  justify-content: center;
  align-items: center;

  :deep(.q-card) {
    max-width: 420px;
    padding: 32px;

    h1 {
      font-size: 2rem;
      line-height: 1;
      margin-bottom: 2rem;
      font-weight: bold;
      text-transform: uppercase;
    }

    .q-toggle {
      margin-left: -0.75rem;
    }
  }
}
</style>
