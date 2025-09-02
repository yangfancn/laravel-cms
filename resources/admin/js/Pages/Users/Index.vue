<template>
  <Layout>
    <Head title="User Index" />
    <q-markup-table separator="horizontal">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Roles</th>
          <th>Create Time</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in users.data" :key="item.id" class="text-center">
          <td>{{ item.id }}</td>
          <td>{{ item.name }}</td>
          <td></td>
          <td>{{ item.created_at }}</td>
          <td></td>
        </tr>
      </tbody>
    </q-markup-table>
    <div class="q-pa-lg flex-center flex">
      <q-pagination
        :max="users.last_page"
        v-model="currentPage"
        :boundary-links="true"
        :direction-links="true"
        :ellipses="true"
        @update:modelValue="router.get('', { page: $event }, { preserveState: true })"
      ></q-pagination>
    </div>
  </Layout>
</template>

<script lang="ts" setup>
import Layout from "../Layout.vue"
import { Head, router } from "@inertiajs/vue3"
import { ref } from "vue"

const props = defineProps<{
  users: {
    data: {
      id: number
      name: string
      created_at: string
      roles: {
        name: string[]
      }
    }[]
    current_page: number
    total: number
    last_page: number
  }
}>()

const currentPage = ref(props.users.current_page)
</script>
