// Import icon libraries
import "@quasar/extras/material-icons/material-icons.css";
import "@quasar/extras/mdi-v7/mdi-v7.css";
import quasarLang from "quasar/lang/zh-CN";
import { Dialog, LocalStorage, Notify } from "quasar";
// Import Quasar css
import "quasar/src/css/index.sass";
export default {
    plugins: {
        Notify,
        Dialog,
        LocalStorage
    }, // import Quasar plugins and add here
    lang: quasarLang,
    config: {
        brand: {
        // primary: '#000'
        },
        defaults: {}
    }
};
