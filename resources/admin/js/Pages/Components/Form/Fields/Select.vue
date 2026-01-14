<template>
  <q-select
    v-bind="props"
    :options="optionsRef"
    :loading="loading"
    :options-dark="dark"
    @virtualScroll="onScroll"
    @filter="filterFn"
    @new-value="createOption"
  >
    <template v-slot:option="scope">
      <q-item v-bind="scope.itemProps" :class="{ 'border-bottom': scope.opt.disable }">
        <q-item-section>
          <q-item-label>{{ scope.opt.label }}</q-item-label>
          <q-item-label caption>{{ scope.opt.description }}</q-item-label>
        </q-item-section>
      </q-item>
    </template>
    <template v-if="prependIcon" #prepend>
      <q-icon :name="prependIcon"></q-icon>
    </template>
    <template v-if="appendIcon" #append>
      <q-icon :name="appendIcon"></q-icon>
    </template>
  </q-select>
</template>

<script lang="ts" setup>
import { ref, onMounted } from "vue"
import { QSelect, type QSelectProps, useQuasar } from "quasar"
import axios, { isAxiosError } from "axios"

interface Option {
  label: string | number
  value: string | number
}

interface selectOptions extends Omit<QSelectProps, "dark"> {
  xhrOptionsUrl?: string
  xhrCreateOptionUrl?: string
  appendIcon?: string
  prependIcon?: string
  options: Option[]
  dark?: boolean
}

const props = defineProps<selectOptions>()

const loading = ref<boolean>(false)
const nextPage = ref(2)
const optionsRef = ref<Option[]>([...props.options])
const search = ref<string>("")
const oldSearch = ref<string>("")
const $q = useQuasar()

const xhrLoadOptions = async (clear = false) => {
  loading.value = true
  // clear options,like in search
  if (clear) {
    optionsRef.value.splice(0, optionsRef.value.length)
  }

  const mv: any = props.modelValue as unknown
  const required: (number | string)[] | null =
    nextPage.value === 1
      ? Array.isArray(mv)
        ? mv
        : mv && typeof mv === "object"
          ? Object.values(mv)
          : mv == null
            ? []
            : [mv]
      : null
  await axios
    .request<{
      options: {
        label: string | number
        value: string | number
      }[]
    }>({
      url: props.xhrOptionsUrl,
      method: "get",
      params: {
        page: nextPage.value,
        pageSize: 15,
        require: required,
        search: search.value
      }
    })
    .then(({ data }) => {
      if (data.options.length) {
        nextPage.value++
        optionsRef.value.push(...data.options)
      }
    })
    .finally(() => {
      loading.value = false
    })
}

onMounted(() => {
  if (props.xhrOptionsUrl && props.options.length === 0) {
    nextPage.value = 1
    void xhrLoadOptions()
  }
})

const onScroll = async ({ index, to }: { index: number | string; to: number | string }) => {
  if (props.xhrOptionsUrl && !loading.value && index === to) {
    await xhrLoadOptions()
  }
}

const filterFn = async (val: string, update: (callbackFn: () => void, afterFn?: (ref: QSelect) => void) => void) => {
  if (props.xhrOptionsUrl) {
    if (oldSearch.value !== val) {
      search.value = val
      nextPage.value = 1
      await xhrLoadOptions(true)
    }

    update(
      () => (oldSearch.value = val),
      (ref) => props.newValueMode === undefined && ref.setOptionIndex(0)
    )
  } else {
    update(() => {
      const needle = val.toLowerCase()
      optionsRef.value = props.options.filter((item: Option) => item.label.toString().toLowerCase().includes(needle))
    })
  }
}

const createOption = (value: string, done: (item: any, mode: "add" | "add-unique" | "toggle" | undefined) => void) => {
  if (props.xhrCreateOptionUrl) {
    axios
      .post<{
        label: string | number
        value: string | number
      }>(props.xhrCreateOptionUrl, {
        name: value
      })
      .then(({ data }) => {
        optionsRef.value.push(data)
        done(data.value, props.newValueMode)
      })
      .catch((err: unknown) => {
        let message = "Request failed"
        if (isAxiosError(err)) {
          const data = err.response?.data as { message?: unknown } | undefined
          const msg = data?.message
          message = typeof msg === "string" ? msg : (err.message ?? message)
        }
        $q.notify({ message, type: "negative" })
      })
  } else {
    done({ label: value, value }, props.newValueMode)
  }
}
</script>

<style scoped>
.border-bottom {
  border-bottom: 1px solid #ddd;
}
</style>
