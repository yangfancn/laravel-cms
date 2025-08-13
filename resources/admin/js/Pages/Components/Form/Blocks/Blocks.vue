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
    <q-card-section v-if="reactiveData.length">
      <VueDraggable
        v-model="reactiveData"
        :sort="reorder"
        handle=".drag-handler"
        @update:modelValue="sortedUpdate"
        :on-update="indexChange"
        :animation="300"
      >
        <Block
          class="q-mb-md"
          v-for="(item, key) in reactiveData"
          :expand-name="`${expandName}.${key}`"
          :key="uniqueIds[key]"
          :model-value="item"
          :fields="fields"
          :reorder="reorder"
          @delete="deleteBlock(Number(key))"
        ></Block>
      </VueDraggable>
    </q-card-section>
    <q-card-actions align="center" v-show="allowAdd">
      <q-btn rounded color="teal" icon="mdi-plus" @click="appendDefaultDataBlock"></q-btn>
    </q-card-actions>
    <q-card-section v-if="_error">
      <div class="text-negative">{{ _error }}</div>
    </q-card-section>
  </q-card>
</template>

<script lang="ts" setup>
import { computed, inject, onBeforeMount, ref } from "vue"
import { SortableEvent, VueDraggable } from "vue-draggable-plus"
import Block from "./Block.vue"
import { useQuasar } from "quasar"
import { trans } from "laravel-vue-i18n"
import { v4 as uuid } from "uuid"

interface Props {
  name: string | null
  expandName: string
  title: string | null
  repeat: boolean
  min: number | null
  max: number | null
  reorder: boolean
  fields: any
  modelValue: any
}

const $q = useQuasar()

const props = withDefaults(defineProps<Props>(), {
  modelValue: []
})

const emit = defineEmits(["update:modelValue"])

let reactiveData = computed({
  get: (): any[] => props.modelValue ?? [],
  set: (data: any) => {
    emit("update:modelValue", data)
    clearError(props.expandName)
  }
})

const uniqueIds = ref((props.modelValue ?? []).map(() => uuid()))
const sortError = inject("sortError") as (
  prefixName: string,
  newIndex: number,
  oldIndex: number
) => void
const getError = inject("getError") as (name: string) => string | null
const clearError = inject("clearError") as (name: string) => void
const _error = computed(() => getError(props.expandName))

const allowDelete = () => {
  return !props.min || reactiveData.value.length > props.min
}

const allowAdd = computed(() => {
  return !props.max || reactiveData.value.length !== props.max
})

onBeforeMount(() => {
  if (props.min && reactiveData.value.length < props.min) {
    for (let i = 0; i < props.min - reactiveData.value.length; i++) {
      appendDefaultDataBlock()
    }
  }
})

const appendDefaultDataBlock = () => {
  if (props.max && reactiveData.value.length >= props.max) {
    $q.notify({
      message: trans("messages.overMaxItems:max", { max: props.max.toString() }),
      type: "warning"
    })
    return
  }
  reactiveData.value = [...reactiveData.value, {}]
  uniqueIds.value.push(uuid())
}

const deleteBlock = (index: number) => {
  if (!allowDelete()) {
    $q.notify({
      message: trans("messages.lessThanMinItems:min", { min: props.min!.toString() }),
      type: "warning"
    })
    return
  }
  reactiveData.value.splice(index, 1)
  uniqueIds.value.splice(index, 1)
}

const sortedUpdate = () => {
  uniqueIds.value = reactiveData.value.map(() => uuid())
}

const indexChange = (evt: SortableEvent) => {
  sortError(props.expandName, evt.newIndex!, evt.oldIndex!)
}
</script>
