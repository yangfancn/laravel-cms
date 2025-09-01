<template>
  <q-field borderless :error="error" :error-message="errorMessage" :label="outLabel">
    <q-range v-bind="props"></q-range>
  </q-field>
</template>

<script lang="ts" setup>
import { type QRangeProps } from "quasar"

interface Props extends QRangeProps {
  outLabel?: string
  error?: boolean
  errorMessage?: string
}

const props = defineProps<Props>()
</script>

<style scoped>
:deep(.q-field__bottom--animated) {
  transform: translateY(10px);
}

:deep(.q-field__label) {
  top: 5px;
}

.q-field--float :deep(.q-field__label) {
  transform: translateY(0) scale(0.75) !important;
}
</style>
