import { createApp } from "vue";
import Vote from "./Vote.vue";
const app = createApp({});
// app.component('vote', Vote).mount('#vote');
document.querySelectorAll(".vote").forEach((el) => {
    app.component("vote", Vote).mount(el);
});
