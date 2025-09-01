import { createApp, type DefineComponent, h } from "vue"
import { createInertiaApp } from "@inertiajs/vue3"
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers"
import { ZiggyVue } from "ziggy-js"
import { Quasar } from "quasar"
import QuasarConfig from "./quasar"
import { i18nVue } from "laravel-vue-i18n"
import { safeRoute } from "./helper"

createInertiaApp({
  resolve: (name) =>
    resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob<DefineComponent>("./Pages/**/*.vue")
    ),
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(Quasar, QuasarConfig)
      .use(ZiggyVue)
      .use(i18nVue as unknown as import("vue").Plugin, {
        resolve: async (lang: string) => {
          const langFiles = import.meta.glob("../../../lang/*.json")
          return langFiles[`../../../lang/${lang}.json`]?.()
        }
      })

    app.config.globalProperties.$safeRoute = safeRoute
    app.mount(el)
  }
}).then().catch((e) => {
  console.error(e)
})
