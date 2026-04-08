import { createApp } from "vue"
import Comments from "./Comments.vue"
import { i18nVue } from "laravel-vue-i18n"

const app = createApp({})

app.component("comments", Comments)

app.use(i18nVue, {
  resolve: (lang: string) => import(`~lang/php_${lang}.json`)
})

app.mount("#comment")
