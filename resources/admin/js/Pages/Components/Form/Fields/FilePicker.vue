<template>
  <q-file v-bind="props" :model-value="modelValue">
    <template v-if="prependIcon" #prepend>
      <q-icon :name="prependIcon"></q-icon>
    </template>
    <template v-if="appendIcon" #append>
      <q-icon :name="appendIcon"></q-icon>
    </template>
  </q-file>
</template>

<script lang="ts" setup>
import { QFileProps } from "quasar"

interface Props extends QFileProps {
  appendIcon?: string
  prependIcon?: string
}

const props = defineProps<Props>()
</script>
