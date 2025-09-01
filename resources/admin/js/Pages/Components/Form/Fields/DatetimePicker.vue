<template>
  <q-input
    v-bind="{ ...props, mask: undefined }"
    :model-value="displayModelValue"
    :mask="inputMask"
  >
    <template v-slot:prepend v-if="hasDate">
      <q-icon :name="prependIcon" class="cursor-pointer">
        <q-popup-proxy cover transition-show="scale" transition-hide="scale">
          <q-date
            :model-value="modelValue"
            :range="range"
            @update:model-value="$emit('update:modelValue', $event)"
            :mask="datetimeMask"
          >
            <div class="row items-center justify-end">
              <q-btn v-close-popup label="Close" color="primary" flat />
            </div>
          </q-date>
        </q-popup-proxy>
      </q-icon>
    </template>

    <template v-slot:append v-if="hasTime && !range">
      <q-icon :name="appendIcon" class="cursor-pointer">
        <q-popup-proxy cover transition-show="scale" transition-hide="scale">
          <q-time
            :model-value="timeModelValue"
            @update:model-value="$emit('update:modelValue', $event)"
            :mask="datetimeMask"
          >
            <div class="row items-center justify-end">
              <q-btn v-close-popup label="Close" color="primary" flat />
            </div>
          </q-time>
        </q-popup-proxy>
      </q-icon>
    </template>
  </q-input>
</template>

<script lang="ts" setup>
import type { QDateProps, QInputProps, QTimeProps } from "quasar"
import { computed } from "vue"

type BaseProps = Omit<QInputProps, "modelValue" | "onUpdate:modelValue"> &
  Omit<QDateProps, "modelValue" | "onUpdate:modelValue"> &
  Omit<QTimeProps, "modelValue" | "onUpdate:modelValue">

// 自定义合并类型
interface CustomProps extends BaseProps {
  prependIcon?: string
  appendIcon?: string
  hasTime: boolean
  hasDate: boolean
  modelValue: string | number | { from: string; to: string } | null | undefined
  "onUpdate:modelValue"?: (value: string | number | { from: string; to: string } | null) => void
}

const props = withDefaults(defineProps<CustomProps>(), {
  prependIcon: "event",
  appendIcon: "access_time"
})

const inputMask = props.range
  ? "####-##-## - ####-##-##"
  : props.hasDate
    ? "####-##-##" + (props.hasTime ? " ##:##" : "")
    : ""

const datetimeMask = props.range
  ? "YYYY-MM-DD"
  : props.hasDate
    ? "YYYY-MM-DD" + (props.hasTime ? " HH:mm" : "")
    : undefined

const displayModelValue = computed<string | null | undefined>(() => {
  const mv = props.modelValue
  if (props.range && mv && typeof mv === "object" && "from" in mv && "to" in mv) {
    const from = typeof mv.from === "string" ? mv.from : String(mv.from ?? "")
    const to = typeof mv.to === "string" ? mv.to : String(mv.to ?? "")
    return `${from} - ${to}`
  }
  if (mv == null) return mv
  if (typeof mv === "string") return mv
  if (typeof mv === "number") return String(mv)
  return null
})

const timeModelValue = computed<string | null | undefined>(() => {
  const mv = props.modelValue
  if (mv == null) return mv
  if (typeof mv === "string") return mv
  if (typeof mv === "number") return String(mv)
  // When range/object is present, time popup is hidden via `!range`,
  // but guard here to satisfy types.
  return undefined
})
</script>
