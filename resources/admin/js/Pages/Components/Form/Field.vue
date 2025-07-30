<template>
  <component
    ref="_component"
    :is="componentType"
    v-bind="{ ...fieldProps, dark: $q.dark.mode }"
    :error="!!_error"
    :errorMessage="_error"
    @update:modelValue="updateModelValue"
    @change="clearError(expandName)"
    :expand-name="expandName"
  ></component>
</template>

<script lang="ts" setup>
import { computed, inject, ref } from "vue"
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
import Uploader from "./Fields/Uploader.vue"
import Editor from "./Fields/Editor.vue"
import Blocks from "./Blocks/Blocks.vue"
import Block from "./Blocks/Block.vue"

interface Props {
  field: string
  expandName: string
  fieldProps: {
    [key: string]: any
  }
}

const $q = useQuasar()
const _component = ref(null)
const props = defineProps<Props>()

const getError = inject("getError") as (name: string) => string | null
const clearError = inject("clearError") as (name: string) => void

const _error = computed(() => getError(props.expandName))

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

const updateModelValue = (data: any) => {
  emit("update:modelValue", data)
}
</script>
