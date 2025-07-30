import { createApp } from "vue"
import Comments from "./Comments.vue"

const app = createApp({})
app.component("comments", Comments).mount("#comment")
