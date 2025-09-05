<template>
  <div ref="_component" class="field-container">
    <component
      :is="componentType"
      v-bind="{ ...fieldProps, dark: $q.dark.mode }"
      :error="!!_error"
      :errorMessage="_error"
      @update:modelValue="updateModelValue"
      @change="clearError(expandName)"
      :expand-name="expandName"
    ></component>
  </div>
</template>

<script lang="ts" setup>
import { computed, ref, nextTick, onMounted, onBeforeUnmount, defineAsyncComponent } from "vue"
import type { Component } from "vue"
import { useQuasar } from "quasar"
import Input from "./Fields/Input.vue"
import Select from "./Fields/Select.vue"
import FilePicker from "./Fields/FilePicker.vue"
import Radio from "./Fields/Radio.vue"
import Checkbox from "./Fields/Checkbox.vue"
import Toggle from "./Fields/Toggle.vue"
import Slider from "./Fields/Slider.vue"
import Range from "./Fields/Range.vue"
import DatetimePicker from "./Fields/DatetimePicker.vue"
import ColorPicker from "./Fields/ColorPicker.vue"
// heavy components lazy-loaded to reduce initial chunk size
const Uploader = defineAsyncComponent(() => import("./Fields/Uploader.vue"))
const Editor = defineAsyncComponent(() => import("./Fields/Editor.vue"))
const Blocks = defineAsyncComponent(() => import("./Blocks/Blocks.vue"))
const Block = defineAsyncComponent(() => import("./Blocks/Block.vue"))
import { useFormContext } from "./useFormContext"

interface Props {
  field: string
  expandName: string
  fieldProps: Record<string, any>
}

const $q = useQuasar()
const _component = ref<HTMLElement | null>()
const props = defineProps<Props>()

const { getError, clearError, registerField, unregisterField } = useFormContext()

const _error = computed(() => {
  if (props.field === "select" && props.fieldProps.multiple) {
    return getError(props.expandName, false)
  }
  return getError(props.expandName, true)
})

const emit = defineEmits(["update:modelValue", "clear-error"])

const _components: Record<string, Component> = {
  input: Input,
  select: Select,
  filePicker: FilePicker,
  radio: Radio,
  checkbox: Checkbox,
  toggle: Toggle,
  slider: Slider,
  range: Range,
  datetime: DatetimePicker,
  color: ColorPicker,
  uploader: Uploader,
  ckEditor: Editor,
  blocks: Blocks,
  block: Block
}

const getComponent = (field: string) => {
  return _components[field] || null
}

const componentType = getComponent(props.field)

function focusOnError() {
  void nextTick(() => {
    _component.value?.scrollIntoView({ behavior: "smooth", block: "start" })
  })
}

const updateModelValue = (data: any) => {
  emit("update:modelValue", data)
}

onMounted(() => {
  registerField(props.expandName, focusOnError)
})

onBeforeUnmount(() => {
  unregisterField(props.expandName)
})
</script>

<style scoped>
.field-container {
  scroll-margin-top: 50px;
}
</style>
