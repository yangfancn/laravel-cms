<template>
  <q-btn v-bind="props" @click="handleClick"></q-btn>
</template>

<script lang="ts" setup>
import { QBtnProps, useQuasar } from "quasar"
import { router } from "@inertiajs/vue3"
import { RouteParams } from "ziggy-js"
import { safeRoute } from "../../../helper"

interface Props extends QBtnProps {
  route?: string
  routeParam?: RouteParams<any>
  withConfirm?: boolean
  method?: InertiaRequestMethod
  requestData?: {
    [key: string]: any
  }
  confirmMessage?: string
  routeParamName?: string
}

const props = withDefaults(defineProps<Props>(), {
  confirmMessage: "Are you sure to do this?"
})
const $q = useQuasar()

const handleClick = () => {
  if (!props.route) {
    return
  }
  if (props.withConfirm) {
    $q.dialog({
      title: "Confirm",
      message: props.confirmMessage,
      cancel: true,
      persistent: true,
      class: "confirm-delete-dialog",
      color: "#fff"
    }).onOk(() => {
      goto()
    })
  } else {
    goto()
  }
}

const goto = () =>
  router.visit(safeRoute(props.route!, props.routeParam), {
    method: props.method ?? "get",
    data: props.requestData ?? {}
  })
</script>
