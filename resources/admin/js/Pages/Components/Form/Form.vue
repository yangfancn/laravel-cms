<template>
  <q-form @submit.prevent="submitForm">
    <q-linear-progress v-if="form.progress" :value="form.progress.percentage"></q-linear-progress>
    <div class="content">
      <div class="row q-gutter-y-sm">
        <div v-for="(item, key) in fields" :class="'col-' + item.cols" :key="`${item.field}-${key}`">
          <Field
            :field="item.field"
            :field-props="{ ...item, modelValue: form[item.name] }"
            :expand-name="item.name"
            @update:modelValue="form[item.name] = $event"
            @change="form.validate(item.name)"
          ></Field>
        </div>
        <slot name="submit">
          <!-- prevent scroll after press enter -->
          <button type="submit" :disabled="form.processing" hidden></button>
          <q-btn @click="submitForm" :disable="form.processing">Submit</q-btn>
        </slot>
      </div>
    </div>
  </q-form>
</template>

<script setup lang="ts">
import { useForm } from "laravel-precognition-vue-inertia"
import type { FormProps } from "../../../types/propTypes"
import { useQuasar } from "quasar"
import { nextTick, provide, ref } from "vue"
import { trans } from "laravel-vue-i18n"
import Field from "./Field.vue"
import { provideFormContext, type FormErrors } from "./useFormContext"

const props = defineProps<FormProps>()

interface SubmitOptions {
  onFinish?: () => void
  onError?: (errors: FormErrors) => void | Promise<void>
  preserveScroll?: boolean | "errors"
  preserveState?: boolean | "errors"
  replace?: boolean
}
type FormInstance = Record<string, unknown> & {
  submit: (opts: SubmitOptions) => void
  reset: (...fields: string[]) => void
  validate: (field: string) => void
  processing: boolean
  progress?: { percentage: number } | null
  errors: FormErrors
}

const form = useForm(props.method.toLowerCase() as InertiaRequestMethod, props.action, props.data, {
  onBefore: () => props.precognitive
}) as unknown as FormInstance

const $q = useQuasar()
const allowSubmitHandlers = ref<(() => boolean)[]>([])

function submitForm() {
  for (const handler of allowSubmitHandlers.value) {
    if (!handler()) {
      $q.notify({
        message: trans("messages.formHasProcessing"),
        type: "negative",
        position: "center"
      })
      return
    }
  }

  form.submit({
    onFinish: () => form.reset("password"),
    onError: async (errors: FormErrors) => {
      //这里可以改成 provide() 一个方法给全局，需要用到 error focus 的组件调用它，传递过来方法，在这里调用
      await nextTick()
      focusFirstErrorField(errors)
    },
    preserveScroll: "errors",
    preserveState: "errors",
    replace: true
  })
}

const getItemError = (name: string, strict = true): string | null => {
  if (strict) {
    return form.errors[name] ?? null
  }

  const errors: string[] = []

  // 父字段
  if (Object.prototype.hasOwnProperty.call(form.errors, name)) {
    const v = form.errors[name]
    if (typeof v === "string") errors.push(v)
  }

  // 所有子字段
  const prefix = name + "."
  Object.keys(form.errors).forEach((key) => {
    if (key.startsWith(prefix)) {
      const v = form.errors[key]
      if (typeof v === "string") errors.push(v)
    }
  })

  return errors.length > 0 ? errors.join("; ") : null
}

const clearError = (name: string) => {
  form.errors[name] = null
}

const sortError = (prefixName: string, newIndex: number, oldIndex: number) => {
  const updatedErrors: FormErrors = {}

  for (const key in form.errors) {
    const match = new RegExp(`^${prefixName}\\.([0-9]+)\\.`).exec(key)
    if (match) {
      const index = parseInt(match[1], 10)
      let newKey = key
      if (index === oldIndex) {
        newKey = key.replace(`${prefixName}.${oldIndex}.`, `${prefixName}.${newIndex}.`)
      } else if (index > oldIndex && index <= newIndex) {
        newKey = key.replace(`${prefixName}.${index}.`, `${prefixName}.${index - 1}.`)
      } else if (index < oldIndex && index >= newIndex) {
        newKey = key.replace(`${prefixName}.${index}.`, `${prefixName}.${index + 1}.`)
      }
      updatedErrors[newKey] = form.errors[key] ?? null
    } else {
      updatedErrors[key] = form.errors[key] ?? null
    }
  }
  form.errors = updatedErrors
}

const addAllowSubmitHandler = (handler: () => boolean) => {
  allowSubmitHandlers.value.push(handler)

  return () => {
    const index = allowSubmitHandlers.value.indexOf(handler)
    if (index !== -1) {
      allowSubmitHandlers.value.splice(index, 1)
    }
  }
}

const fieldRefs = ref<{ name: string; focusOnError: () => void }[]>([])

function registerField(name: string, focusOnError: () => void) {
  fieldRefs.value.push({ name, focusOnError })
}

function unregisterField(name: string) {
  const idx = fieldRefs.value.findIndex((f) => f.name === name)
  if (idx !== -1) {
    fieldRefs.value.splice(idx, 1)
  }
}

function focusFirstErrorField(errors: Record<string, unknown>) {
  for (const { name, focusOnError } of fieldRefs.value) {
    const hasDirectError = Object.prototype.hasOwnProperty.call(errors, name)
    const hasNestedError = Object.keys(errors).some((key) => key.startsWith(name + "."))

    if (hasDirectError || hasNestedError) {
      focusOnError()
      break
    }
  }
}

provide("registerField", registerField)
// Back-compat for existing components still using string keys
provide("unregisterField", unregisterField)
provide("getError", getItemError)
provide("clearError", clearError)
provide("sortError", sortError)
provide("addAllowSubmitHandler", addAllowSubmitHandler)

provideFormContext({
  registerField,
  unregisterField,
  getError: getItemError,
  clearError,
  sortError,
  addAllowSubmitHandler
})
</script>
