<template>
  <Layout :title="options.title ?? undefined">
    <q-table
      :rows="options.data.data"
      :columns="options.columns"
      :title="options.title ?? undefined"
      v-model:pagination="pagination"
      :selection="options.batchDeleteRoute ? 'multiple' : 'none'"
      v-model:selected="selected"
      selection-color="primary"
      @request="requestHandler"
      binary-state-sort
    >
      <template v-slot:top>
        <div class="q-table__title">{{ options.title }}</div>
        <q-space />
        <Button
          v-if="options.createRoute"
          icon="mdi-plus"
          dense
          :flat="true"
          :route="options.createRoute"
        ></Button>
        <q-btn
          v-if="options.batchDeleteRoute && selectedIds?.length"
          icon="mdi-delete"
          color="negative"
          dense
          :flat="true"
          @click="batchDeleteHandler"
          style="margin-right: 1rem"
        >
        </q-btn>
        <template v-for="item in options.selectOptions">
          <q-select
            :options="item.options"
            :label="item.name"
            style="min-width: 10rem"
            v-model="item.modelValue"
            emit-value
            map-options
            clearable
            dense
            outlined
            autocomplete=""
          />
        </template>

        <q-input
          v-if="options.allowSearch"
          dense
          outlined
          @keydown.enter="requestHandler"
          v-model="options.filter"
          placeholder="Search"
          class="search"
        >
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>
      <template v-slot:header-selection="scope">
        <q-checkbox v-model="scope.selected" color="teal-8" />
      </template>
      <template v-slot:body-selection="scope">
        <q-checkbox v-model="scope.selected" color="teal-3" />
      </template>
      <template v-slot:body-cell="props">
        <q-td :align="props.col.align">
          <!--    Image      -->
          <template v-if="props.col.type === 'image'">
            <img
              :src="parseCellValue(props)"
              alt=""
              :width="props.col.width"
              :height="props.col.height"
            />
          </template>
          <!--    Link      -->
          <template v-else-if="props.col.type === 'link'">
            <Link :href="props.row[props.col.urlField]" :title="parseCellValue(props)"> </Link>
          </template>
          <!--    Tags      -->
          <template v-else-if="props.col.type === 'chips'">
            <q-chip
              v-for="(chip, index) in parseCellValue(props)"
              :key="`${props.row[props.col.id]}-${index}`"
              :ripple="false"
              :label="chip"
            ></q-chip>
          </template>
          <!--    Text      -->
          <template v-else>
            <span v-html="parseCellValue(props)"></span>
          </template>
        </q-td>
      </template>
      <!--    Actions   -->
      <template v-slot:body-cell-actions="props">
        <q-td>
          <template
            v-for="(btn, index) in options.rowButtons"
            :key="`${props.row.id}-button-${index}`"
          >
            <Button
              v-if="btn.type === 'status-button'"
              v-bind="computedStatusBtnProps(props.row[btn.statusFiled!] as boolean, btn)"
              :routeParam="btn.routeParamName ? props.row[btn.routeParamName] : undefined"
              :dense="true"
            />
            <Button
              v-else
              v-bind="btn"
              :routeParam="btn.routeParamName ? props.row[btn.routeParamName] : undefined"
              :dense="true"
            />
          </template>
        </q-td>
      </template>
    </q-table>
  </Layout>
</template>

<script lang="ts" setup>
import Layout from "./Layout.vue"
import { Link, router } from "@inertiajs/vue3"
import { computed, ref, watch } from "vue"
import { QBtnProps, QSelectProps, QSelect, useQuasar } from "quasar"
import Button from "./Components/Table/Button.vue"
import { safeRoute } from "../helper"
import { trans } from "laravel-vue-i18n"

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
  modelValue: any
}

interface Props {
  options: {
    title: string | null
    columns: {
      align: "left" | "center" | "right"
      name: string
      field: string
      label: string
      sortable: boolean
      type: "text" | "image" | "link" | "chips" | "rowActions"
      urlField?: string
      width?: string
      height?: string
      radius?: string
      format?: (val: any, row: { [key: string]: any }) => string
    }[]
    data: {
      data: {
        [key: string]: any
      }[]
    }
    pagination: {
      currentPage: number
      perPage: number
      total: number
    }
    createRoute?: string
    batchDeleteRoute?: string
    rowButtons?: ButtonProps[]
    filter?: string
    allowSearch?: boolean
    sortBy?: string
    descending?: boolean
    selectOptions?: selectOptions[]
  }
}

interface ButtonProps extends QBtnProps {
  route?: string
  routeParamName?: string
  withConfirm?: boolean
  method?: InertiaRequestMethod
  type: string
  positiveLabel?: string
  positiveIcon?: string
  positiveColor?: string
  negativeLabel?: string
  negativeIcon?: string
  negativeColor?: string
  statusFiled?: string
}

interface RequestPagination {
  page: number
  rowsPerPage: number
  sortBy?: string
  descending?: boolean
}

interface Pagination extends RequestPagination {
  rowsNumber: number
}

const props = defineProps<Props>()
const $q = useQuasar()
const selected = ref<
  {
    [key: string]: any
  }[]
>()

console.log(props.options.selectOptions)
const group = ref(2)

const pagination = ref<Pagination>({
  page: props.options.pagination.currentPage,
  rowsPerPage: props.options.pagination.perPage,
  rowsNumber: props.options.pagination.total,
  sortBy: props.options.sortBy,
  descending: props.options.descending
})

const selectedIds = computed(() => selected.value?.map((item) => item.id))

const computedStatusBtnProps = (status: boolean, props: ButtonProps) => {
  return {
    ...props,
    ...(status
      ? {
          label: props.positiveLabel,
          icon: props.positiveIcon,
          color: props.positiveColor
        }
      : {
          label: props.negativeLabel,
          icon: props.negativeIcon,
          color: props.negativeColor
        })
  }
}

const requestHandler = (arg?: {
  pagination: RequestPagination
  filter?: any
  getCellValue: (col: any, row: any) => any
}) => {
  const appendParams = props.options.selectOptions?.reduce(
    (acc: Record<string, any>, curr) => {
      acc[curr.name!] = curr.modelValue
      return acc
    },
    {} as Record<string, any>
  )
  router.get("", {
    ...{
      page: arg?.pagination?.page ?? 1,
      sortBy: arg?.pagination?.sortBy ?? pagination.value.sortBy,
      descending: arg?.pagination?.descending ?? pagination.value.descending,
      search: props.options.filter,
      perPage: arg?.pagination?.rowsPerPage ?? pagination.value.rowsPerPage
    },
    ...appendParams
  })
}

const batchDeleteHandler = () => {
  $q.dialog({
    title: trans("messages.confirm"),
    message: trans("messages.confirmDelete"),
    cancel: true,
    persistent: true
  }).onOk(() => {
    router.delete(safeRoute(props.options.batchDeleteRoute!), {
      preserveState: false,
      data: {
        ids: selected.value?.map((item) => item.id)
      }
    })
  })
}

watch(
  () => props.options.selectOptions,
  (value) => {
    requestHandler()
  },
  { deep: true }
)

const parseCellValue = (props: { [key: string]: any }) => {
  return (
    props.value ??
    props.col.field.split(".").reduce((acc: any, key: string) => acc && acc[key], props.row)
  )
}
</script>

<style lang="scss">
.q-td button {
  margin-right: 0.5rem;

  &:last-child {
    margin-right: 0;
  }
}

.search {
  margin-left: 1rem;
}

td p:last-child {
  margin-bottom: 0;
}
</style>
