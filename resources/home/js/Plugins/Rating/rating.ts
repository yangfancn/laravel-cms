import { createApp } from "vue"
import Rating from "./Rating.vue"

function createRatingApp() {
  const app = createApp({})
  app.component("rating", Rating)
  return app
}

document.querySelectorAll(".hg-rating").forEach((el) => {
  createRatingApp().mount(el)
})
