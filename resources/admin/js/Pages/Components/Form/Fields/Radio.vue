<template>
  <q-field borderless :error="error" :error-message="errorMessage" :label="label">
    <div :class="{ 'q-gutter-sm': true, column: column }">
      <q-radio
        :name="name"
        v-for="item in options"
        :val="item.val"
        :label="item.label"
        :model-value="modelValue"
        :left-label="leftLabel"
        :keep-color="keepColor"
        :checked-icon="checkedIcon"
        :unchecked-icon="unCheckedIcon"
        :size="size"
        :color="color"
        @update:modelValue="$emit('update:modelValue', $event)"
      />
    </div>
  </q-field>
</template>

<script setup lang="ts">
import { QRadioProps } from "quasar"

interface Props {
  label: string
  name: string
  column: boolean
  leftLabel: boolean
  options: QRadioProps[]
  modelValue: any
  keepColor?: boolean
  checkedIcon?: string
  unCheckedIcon?: string
  size?: string
  color?: string
  error?: boolean
  errorMessage?: string
}

const props = withDefaults(defineProps<Props>(), {
  keepColor: false,
  size: "3rem"
})
</script>

<style scoped lang="scss">
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
