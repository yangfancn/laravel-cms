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
import { ref } from "vue"
import { QSelect, QSelectProps, useQuasar } from "quasar"
import axios from "axios"

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
const optionsRef = ref<Option[]>(props.options)
const search = ref<string>("")
const oldSearch = ref<string>("")
const $q = useQuasar()

const xhrLoadOptions = async (clear: boolean = false) => {
  loading.value = true
  // clear options,like in search
  if (clear) {
    optionsRef.value.splice(0, optionsRef.value.length)
  }
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
        require:
          optionsRef.value.map((item) => item.value).indexOf(props.modelValue) === -1
            ? props.modelValue
            : null,
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

if (props.options.length === 0 && props.xhrOptionsUrl) {
  nextPage.value = 1
  xhrLoadOptions()
}

const onScroll = ({ index, to }: { index: number | string; to: number | string }) => {
  if (props.xhrOptionsUrl && !loading.value && index === to) {
    xhrLoadOptions()
  }
}

const filterFn = async (
  val: string,
  update: (callbackFn: () => void, afterFn?: ((ref: QSelect) => void) | undefined) => void
) => {
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
      optionsRef.value = props.options.filter(
        (item: Option) => item.label.toString().toLowerCase().indexOf(needle) > -1
      )
    })
  }
}

const createOption = (
  value: string,
  done: (item: any, mode: "add" | "add-unique" | "toggle" | undefined) => void
) => {
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
      .catch((error) => {
        $q.notify({
          message: error.response.data.message,
          type: "negative"
        })
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
