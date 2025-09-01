import { trans } from "laravel-vue-i18n"
import { route as routeFn } from "ziggy-js"
import { safeRoute } from "../helper"
import { Quasar } from "quasar"

declare module "*.vue" {
  import { DefineComponent } from "vue";
  const component: DefineComponent<object, object, any>;
  export default component;
}

declare module "@vue/runtime-core" {
  interface ComponentCustomProperties {
    $safeRoute: typeof safeRoute
    $t: typeof trans
    route: typeof routeFn
    $q: Quasar
  }
}
