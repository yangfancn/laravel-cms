<template>
  <q-card>
    <template v-if="title">
      <q-card-section>
        <div class="row justify-between items-center">
          <p class="no-margin">
            <i>{{ title }}</i>
          </p>
        </div>
      </q-card-section>
      <q-separator />
    </template>
    <q-card-actions v-else align="between">
      <q-btn v-if="reorder" round icon="mdi-drag" class="drag-handler"></q-btn>
      <q-btn
        round
        class="q-ml-auto"
        icon="mdi-delete-outline"
        color="negative"
        @click="emit('delete')"
      ></q-btn>
    </q-card-actions>
    <q-card-section>
      <div class="row q-gutter-y-sm">
        <div v-for="item in fields" :class="'col-' + item.cols" :key="item.field">
          <Field
            :field="item.field"
            :field-props="{ ...item, modelValue: modelValue[item.name] }"
            @update:model-value="modelValue[item.name] = $event"
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

defineProps<Props>()

const emit = defineEmits(["update:modelValue", "delete", "getError"])
</script>

<style scoped>
  .error {
    color: var(--q-negative);
    font-size: 12px;
  }
</style>
