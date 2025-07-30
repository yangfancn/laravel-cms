<template>
  <q-field
    :error="error"
    :error-message="errorMessage"
    :label="label"
    :style="{ '--min-height': minHeight }"
    borderless
  >
    <ckeditor
      :editor="ClassicEditor"
      :config="editorConfig"
      v-bind:model-value="modelValue ? modelValue : ''"
      @update:model-value="$emit('update:modelValue', $event)"
      @ready="onReady"
    ></ckeditor>
    <template v-if="counter" v-slot:counter>{{ counterText }}</template>
  </q-field>
</template>

<script setup lang="ts">
import {
  Alignment,
  Autoformat,
  BlockQuote,
  Bold,
  ClassicEditor,
  Code,
  CodeBlock,
  Essentials,
  EventInfo,
  FileRepository,
  FontBackgroundColor,
  FontColor,
  FontFamily,
  FontSize,
  GeneralHtmlSupport,
  Heading,
  HorizontalLine,
  Image,
  ImageCaption,
  ImageResize,
  ImageStyle,
  ImageToolbar,
  ImageUpload,
  Indent,
  Italic,
  Link,
  List,
  MediaEmbed,
  Paragraph,
  PasteFromOffice,
  ShowBlocks,
  SourceEditing,
  Strikethrough,
  Subscript,
  Superscript,
  Table,
  TableCaption,
  TableCellProperties,
  TableColumnResize,
  TableProperties,
  TableToolbar,
  TextTransformation,
  TodoList,
  Underline,
  Undo,
  WordCount
} from "ckeditor5"
import { Ckeditor } from "@ckeditor/ckeditor5-vue"
import { MyCustomUploadAdapterPlugin } from "../../../../utils/uploadAdapter"
import { inject, ref } from "vue"
import { Notify, QField } from "quasar"

import "ckeditor5/ckeditor5.css"

interface Props {
  toolbarItems: string[]
  label: string
  name: string
  modelValue: string | null
  error?: boolean
  errorMessage?: string
  counter?: boolean
  minHeight?: string
}

interface EditorCounter {
  words: number
  characters: number
}

const props = withDefaults(defineProps<Props>(), {
  minHeight: "15rem"
})

const addAllowSubmitHandler = inject("addAllowSubmitHandler") as (handler: () => boolean) => void
const counterText = ref(0)

const editorConfig = {
  licenseKey: "GPL",
  plugins: [
    Alignment,
    Autoformat,
    BlockQuote,
    Bold,
    Code,
    Underline,
    Strikethrough,
    Subscript,
    Superscript,
    CodeBlock,
    Essentials,
    FontColor,
    FontFamily,
    FontSize,
    GeneralHtmlSupport,
    Heading,
    FontBackgroundColor,
    HorizontalLine,
    Image,
    ImageCaption,
    ImageStyle,
    ImageToolbar,
    ImageUpload,
    ImageResize,
    Indent,
    Italic,
    Link,
    List,
    TodoList,
    MediaEmbed,
    Paragraph,
    PasteFromOffice,
    SourceEditing,
    Table,
    TableCaption,
    TableCellProperties,
    TableColumnResize,
    TableToolbar,
    TableProperties,
    TextTransformation,
    Undo,
    WordCount,
    ShowBlocks
  ],

  toolbar: {
    items: props.toolbarItems
  },
  image: {
    toolbar: [
      "imageTextAlternative",
      "toggleImageCaption",
      "imageStyle:inline",
      "imageStyle:block",
      "imageStyle:side"
    ]
  },
  table: {
    contentToolbar: [
      "toggleTableCaption",
      "tableColumn",
      "tableRow",
      "mergeTableCells",
      "tableCellProperties",
      "tableProperties"
    ]
  },
  htmlSupport: {},
  extraPlugins: [MyCustomUploadAdapterPlugin],
  wordCount: {
    onUpdate(counter: EditorCounter) {
      counterText.value = counter.characters
    }
  }
}

let fileRepository: FileRepository | null

const onReady = (editor: ClassicEditor) => {
  editor.plugins.get("Notification").on("show:warning", (evt: EventInfo, data) => {
    Notify.create({
      message: data.message,
      type: "negative"
    })
    evt.stop()
  })

  fileRepository = editor.plugins.get("FileRepository")
}

addAllowSubmitHandler(() => {
  return fileRepository?.loaders.last === null
})
</script>

<style scoped lang="scss">
:deep(.ck.ck-editor) {
  flex: 0 0 100%;
}

:deep(.q-field__control-container) {
  padding: 46px 0 0 !important;

  .q-field__label {
    left: 0;
  }
}

.q-field--float :deep(.q-field__label) {
  transform: translateY(-10%) scale(0.75) !important;
}

:deep(.q-field__append) {
  position: absolute;
  right: 12px;
}

.q-field--standout.q-field--dark.q-field--highlighted :deep(.q-field__control) {
  background: rgba(255, 255, 255, 0.07);
}

:deep(.ck.ck-editor__main) {
  color: #333;

  .ck-content {
    min-height: var(--min-height);
  }
}

.q-field--dark {
  --ck-color-base-background: #ddd;
  --ck-color-toolbar-background: #ddd;
}

:deep(.ck.ck-toolbar.ck-toolbar_grouping > .ck-toolbar__items) {
  flex-wrap: wrap;
}
</style>
