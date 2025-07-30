import { trans } from "laravel-vue-i18n"
import { route as routeFn } from "ziggy-js"
import { safeRoute } from "../helper"
import { Quasar } from "quasar"
import { ComponentCustomProperties } from "vue"
import { Composer } from "vue-i18n"

declare module "*.vue" {
  import { defineComponent } from "vue"
  const component: ReturnType<typeof defineComponent>
  export default component
}

declare module "@vue/runtime-core" {
  interface ComponentCustomProperties {
    $safeRoute: typeof safeRoute
    $t: typeof trans
    route: typeof routeFn
    $q: Quasar
  }
}
