import { createApp, type DefineComponent, h } from "vue"
import { createInertiaApp } from "@inertiajs/vue3"
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers"
import { ZiggyVue } from "ziggy-js"
import { Quasar } from "quasar"
import QuasarConfig from "./quasar"
import { i18nVue } from "laravel-vue-i18n"
import { safeRoute } from "./helper"

createInertiaApp({
  resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>("./Pages/**/*.vue")),
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(Quasar, QuasarConfig)
      .use(ZiggyVue)
      .use(i18nVue, {
        resolve: async (lang: string) => {
          const langs = import.meta.glob("~lang/*.json")
          return await langs[`/lang/${lang}.json`]?.()
        }
      })

    app.config.globalProperties.$safeRoute = safeRoute
    app.mount(el)
  }
}).catch((e) => {
  console.error(e)
})
