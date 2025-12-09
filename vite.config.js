import { defineConfig } from "vite"
import laravel from "laravel-vite-plugin"
import vue from "@vitejs/plugin-vue"
import svgLoader from "vite-svg-loader"
import { quasar } from "@quasar/vite-plugin"
import i18n from "laravel-vue-i18n/vite"
import path from "node:path"
import tailwindcss from "@tailwindcss/vite"
import { visualizer } from "rollup-plugin-visualizer"

const isAdmin = process.env.VITE_BUILD_TARGET === "admin"
const isAnalyze = process.env.ANALYZE === "true"

export default defineConfig({
  plugins: [
    laravel({
      input: isAdmin
        ? [
            // backend (admin)
            "resources/admin/js/app.ts",
            "resources/admin/css/app.scss"
          ]
        : [
            // frontend (home)
            "resources/home/js/app.ts",
            "resources/home/js/Plugins/Comments/comments.ts",
            "resources/home/js/Plugins/Vote/vote.ts",
            "resources/home/css/app.css"
          ],
      refresh: ["config/*", "routes/**", "app/Http/Controllers/**", "resources/home/views/**"],
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
    ...(isAdmin ? [svgLoader()] : []),
    // enable Quasar only for admin build
    ...(isAdmin ? [quasar({})] : []),
    // enable Tailwind (and DaisyUI via CSS plugin directive) only for home build
    ...(!isAdmin ? [tailwindcss()] : []),
    // enable bundle visualizer only when ANALYZE=true
    ...(isAnalyze ? [visualizer()] : []),
    i18n()
  ],
  resolve: {
    alias: {
      "@": path.resolve("resources"),
      "~lang": path.resolve("lang"),
      "ziggy-js": path.resolve("vendor/tightenco/ziggy/dist/index.esm.js"),
      vue: "vue/dist/vue.esm-bundler.js"
    }
  },
  build: {
    chunkSizeWarningLimit: 1500
  }
})
