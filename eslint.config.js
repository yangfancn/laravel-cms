import tseslint from "typescript-eslint"
import vue from "eslint-plugin-vue"
import vueParser from "vue-eslint-parser"
import prettierPlugin from "eslint-plugin-prettier/recommended"
import { fileURLToPath } from "node:url"
import path from "node:path"

const __dirname = path.dirname(fileURLToPath(import.meta.url))

export default [
  {
    ignores: [
      "node_modules",
      "public",
      "vendor",
      ".vscode",
      "app",
      "bootstrap",
      "config",
      "database",
      "lang",
      "routes",
      "storage",
      "tests"
    ]
  },
  // js.configs.recommended,
  ...vue.configs["flat/essential"],
  ...tseslint.configs.recommended,
  // ...tseslint.configs.stylisticTypeChecked,

  // 对 .vue：用 vue 解析器包裹 ts 解析器，且绑定 tsconfig
  {
    files: ["**/*.vue"],
    languageOptions: {
      parser: vueParser,
      parserOptions: {
        parser: tseslint.parser,
        extraFileExtensions: [".vue"],
        tsconfigRootDir: __dirname,
        projectService: true,
        project: [path.join(__dirname, "tsconfig.eslint.json")],
        ecmaVersion: "latest",
        sourceType: "module"
      }
    },
    rules: {
      "vue/no-mutating-props": "off",
      "vue/attributes-order": "off",
      "vue/no-v-html": "off"
    }
  },

  // 对 .ts：直接用 ts 解析器，且绑定同一 tsconfig
  {
    files: ["**/*.ts"],
    languageOptions: {
      parser: tseslint.parser,
      parserOptions: {
        tsconfigRootDir: __dirname,
        projectService: true,
        project: [path.join(__dirname, "tsconfig.eslint.json")],
        ecmaVersion: "latest",
        sourceType: "module"
      }
    }
  },
  {
    rules: {
      "@typescript-eslint/no-explicit-any": "off",
      "no-undef": "off",
      "vue/multi-word-component-names": "off"
    }
  },
  prettierPlugin
]
