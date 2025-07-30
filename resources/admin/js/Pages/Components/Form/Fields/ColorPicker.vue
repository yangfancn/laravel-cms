<template>
  <q-input v-bind="props">
    <template v-slot:prepend>
      <div class="color-preview" :style="{ backgroundColor: modelValue ?? 'transparent' }"></div>
    </template>
    <template v-slot:append>
      <q-icon name="colorize" class="cursor-pointer">
        <q-popup-proxy cover transition-show="scale" transition-hide="scale">
          <q-color
            :model-value="modelValue"
            @update:model-value="$emit('update:modelValue', $event)"
            :default-value="defaultView"
            :palette="palette"
            :no-header="noHeader"
            :no-header-tabs="noHeaderTabs"
            :no-footer="noFooter"
          />
        </q-popup-proxy>
      </q-icon>
    </template>
  </q-input>
</template>

<script lang="ts" setup>
import { QColorProps, QInputProps } from "quasar"

type Props = (QInputProps & QColorProps) & {
  modelValue: string | null
  "onUpdate:modelValue"?: (value: string | null) => void
}

const props = defineProps<Props>()
</script>

<style scoped>
.q-field--standout.q-field--dark.q-field--highlighted :deep(.q-field__control) {
  background: rgba(255, 255, 255, 0.07);
}

.q-field--standout.q-field--dark.q-field--highlighted :deep(.q-field__native) {
  color: #fff;
}

.color-preview {
  width: 1.5rem;
  height: 1.5rem;
  border-radius: 3px;
}
</style>
