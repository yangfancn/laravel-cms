<template>
  <q-field borderless :error="error" :error-message="errorMessage">
    <q-toggle v-bind="props" :model-value="Boolean(modelValue)" />
  </q-field>
</template>

<script setup lang="ts">
import { QToggleProps } from "quasar"

interface Props extends QToggleProps {
  modelValue: boolean | number
  error?: boolean
  errorMessage?: string
}

const props = defineProps<Props>()
</script>

<style scoped>
.row.reverse {
  margin-right: auto;
}

:deep(.q-field__bottom--animated) {
  transform: translateY(10px);
}

:deep(.q-field__label) {
  top: 10px;
}

.q-field--float :deep(.q-field__label) {
  transform: translateY(0) scale(0.75) !important;
}
</style>
