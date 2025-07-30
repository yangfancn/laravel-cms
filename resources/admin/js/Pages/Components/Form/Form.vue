<template>
  <q-form @submit.prevent="submitFrom" ref="formRef">
    <q-linear-progress v-if="form.progress" :value="form.progress.percentage"></q-linear-progress>
    <div class="content">
      <div class="row q-gutter-y-sm">
        <div
          v-for="(item, key) in fields"
          :class="'col-' + item.cols"
          :key="`${item.field}-${key}`"
        >
          <Field
            :field="item.field"
            :field-props="{ ...item, modelValue: form[item.name] }"
            :expand-name="item.name"
            @update:modelValue="form[item.name] = $event"
            @change="form.validate(item.name)"
          ></Field>
        </div>
        <slot name="submit">
          <q-btn type="submit" :disable="form.processing">Submit</q-btn>
        </slot>
      </div>
    </div>
  </q-form>
</template>

<script setup lang="ts">
import { useForm } from "laravel-precognition-vue-inertia"
import type { FormProps } from "../../../types/propTypes"
import { QForm, useQuasar } from "quasar"
import { nextTick, provide, ref } from "vue"
import { trans } from "laravel-vue-i18n"
import Field from "./Field.vue"

const props = defineProps<FormProps>()
// console.log(props.data)
const form = useForm(props.method.toLowerCase() as InertiaRequestMethod, props.action, props.data)
const $q = useQuasar()
const allowSubmitHandlers = ref<(() => boolean)[]>([])
const formRef = ref<QForm>()

function submitFrom() {
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
    onError: async (errors: FormProps["errors"]) => {
      //这里可以改成 provide() 一个方法给全局，需要用到 error focus 的组件调用它，传递过来方法，在这里调用
      await nextTick()
      for (const errorsKey in errors) {
        const ele = formRef.value?.$el.querySelector(`[expand-name="${errorsKey}"]`)
        if (ele) {
          ele.scrollIntoView({ behavior: "smooth", block: "start" })
          break
        }
      }
    },
    preserveScroll: "errors",
    preserveState: "errors",
    replace: true
  })
}

const getItemError = (name: string): string | null => {
  return form.errors[name] ?? null
}

const clearError = (name: string) => {
  form.errors[name] = null
}

const sortError = (prefixName: string, newIndex: number, oldIndex: number) => {
  const updatedErrors: Record<string, any> = {}

  for (const key in form.errors) {
    const match = key.match(new RegExp(`^${prefixName}\\.([0-9]+)\\.`))
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
      updatedErrors[newKey] = form.errors[key]
    } else {
      updatedErrors[key] = form.errors[key]
    }
  }
  form.errors = updatedErrors
}

const addAllowSubmitHandler = (handler: () => boolean) => {
  allowSubmitHandlers.value.push(handler)
}

provide("getError", getItemError)
provide("clearError", clearError)
provide("sortError", sortError)
provide("addAllowSubmitHandler", addAllowSubmitHandler)
</script>
