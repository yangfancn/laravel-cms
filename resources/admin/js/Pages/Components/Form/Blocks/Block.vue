<template>
  <q-card>
    <template v-if="title">
      <q-card-section>
        <div class="row items-center justify-between">
          <p class="no-margin">
            <i>{{ title }}</i>
          </p>
        </div>
      </q-card-section>
      <q-separator />
    </template>
    <q-card-actions v-else align="between">
      <q-btn v-if="reorder" round icon="mdi-drag" class="drag-handler"></q-btn>
      <q-btn round class="q-ml-auto" icon="mdi-delete-outline" color="negative" @click="emit('delete')"></q-btn>
    </q-card-actions>
    <q-card-section>
      <div class="row q-gutter-y-sm q-col-gutter-x-md">
        <div v-for="item in fields" :class="'col-' + item.cols" :key="item.field">
          <Field
            :field="item.field"
            :field-props="{ ...item, modelValue: modelValue[item.name] }"
            @update:model-value="onFieldUpdate(item.name, $event)"
            :expand-name="`${expandName}.${item.name}`"
          ></Field>
        </div>
      </div>
      <div class="error" v-if="errorMessage">
        <div role="alert">{{ errorMessage }}</div>
      </div>
    </q-card-section>
  </q-card>
</template>

<script lang="ts" setup>
import Field from "../Field.vue"

interface Props {
  modelValue: Record<string, any>
  fields: any
  expandName: string
  name?: string
  title?: string
  reorder?: boolean
  errorMessage?: string
}

import { onMounted } from "vue"

const props = defineProps<Props>()

const emit = defineEmits(["update:modelValue", "delete", "getError"])

const onFieldUpdate = (name: string, value: any) => {
  const base = { ...(props.modelValue || {}) }
  if (value === undefined || value === "") {
    base[name] = null
  } else {
    base[name] = value
  }
  emit("update:modelValue", base)
}

onMounted(() => {
  const base = { ...(props.modelValue || {}) }
  let changed = false
  for (const item of props.fields || []) {
    const key = item.name
    if (!Object.prototype.hasOwnProperty.call(base, key)) {
      base[key] = null
      changed = true
    }
  }
  if (changed) {
    emit("update:modelValue", base)
  }
})
</script>

<style scoped>
.error {
  color: var(--q-negative);
  font-size: 12px;
}
</style>
