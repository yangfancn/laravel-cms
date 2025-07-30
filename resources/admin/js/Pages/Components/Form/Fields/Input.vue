<template>
  <q-input
    v-bind="props"
    :model-value="modelValue"
    :type="type === 'password' ? (showPassword ? 'text' : 'password') : type"
  >
    <template v-if="prependIcon" #prepend>
      <q-icon :name="prependIcon"></q-icon>
    </template>
    <template v-if="type === 'password'" #append>
      <q-icon
        :name="showPassword ? 'visibility_off' : 'visibility'"
        class="cursor-pointer"
        @click="showPassword = !showPassword"
      />
    </template>
    <template v-else-if="appendIcon" #append>
      <q-icon :name="appendIcon"></q-icon>
    </template>
  </q-input>
</template>

<script setup lang="ts">
import { QInputProps } from "quasar"
import { ref } from "vue"

interface Props extends QInputProps {
  appendIcon?: string
  prependIcon?: string
  expandName?: string
}

const props = defineProps<Props>()
const showPassword = ref(false)
</script>
