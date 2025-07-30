import { defineConfig } from "vite"
import laravel from "laravel-vite-plugin"
import vue from "@vitejs/plugin-vue"
import svgLoader from "vite-svg-loader"
import { visualizer } from "rollup-plugin-visualizer"
import { quasar } from "@quasar/vite-plugin"
import i18n from "laravel-vue-i18n/vite"
import path from "node:path"
import tailwindcss from "@tailwindcss/vite"

const isAdmin = process.env.VITE_BUILD_TARGET === "admin"

export default defineConfig({
  server: {
    host: "127.0.0.1",
    port: 5173,
    strictPort: true,
    cors: {
      origin: "http://127.0.0.1:8000",
      credentials: true
    }
  },
  plugins: [
    laravel({
      input: isAdmin
        ? [
            //backend
            "resources/admin/js/app.ts",
            "resources/admin/css/app.scss"
          ]
        : [
            //frontend
            "resources/home/js/app.ts",
            "resources/home/js/Plugins/Comments/comments.ts",
            "resources/home/js/Plugins/Vote/vote.ts",
            "resources/home/css/app.css"
          ],
      refresh: true,
      buildDirectory: isAdmin ? "build/admin" : "build/home"
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false
        }
      }
    }),
    svgLoader(),
    visualizer(),
    quasar({
      // sassVariables: 'resources/admin/css/quasar.variables.sass'
    }),
    i18n(),
    tailwindcss()
  ],
  resolve: {
    alias: {
      "@": "./resources",
      "~lang": "./lang",
      "ziggy-js": path.resolve("vendor/tightenco/ziggy/dist/index.esm.js"),
      vue: "vue/dist/vue.esm-bundler.js"
    }
  }
})
