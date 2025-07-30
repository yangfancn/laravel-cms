<template>
  <div
    :class="{ 'comment-editor': true, 'is-active': isFocus, 'has-error': errorMessage }"
    class="border rounded-lg p-3 mt-10"
  >
    <div class="editor">
      <editor-content :editor="editor" />
    </div>
    <div class="toolbar hidden flex-wrap justify-between items-center" @mousedown.prevent>
      <div class="format flex flex-nowrap justify-start items-center">
        <button
          @click="editor?.chain().focus().toggleBold().run()"
          :class="{ 'is-active': editor?.isActive('bold') }"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="24px"
            viewBox="0 -960 960 960"
            width="24px"
            fill="#5f6368"
          >
            <path
              d="M272-200v-560h221q65 0 120 40t55 111q0 51-23 78.5T602-491q25 11 55.5 41t30.5 90q0 89-65 124.5T501-200H272Zm121-112h104q48 0 58.5-24.5T566-372q0-11-10.5-35.5T494-432H393v120Zm0-228h93q33 0 48-17t15-38q0-24-17-39t-44-15h-95v109Z"
            />
          </svg>
        </button>
        <button
          @click="editor?.chain().focus().toggleItalic().run()"
          :class="{ 'is-active': editor?.isActive('italic') }"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="24px"
            viewBox="0 -960 960 960"
            width="24px"
            fill="#5f6368"
          >
            <path d="M200-200v-100h160l120-360H320v-100h400v100H580L460-300h140v100H200Z" />
          </svg>
        </button>
        <button @click="editor?.chain().focus().setHorizontalRule().run()">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="24px"
            viewBox="0 -960 960 960"
            width="24px"
            fill="#5f6368"
          >
            <path d="M160-440v-80h640v80H160Z" />
          </svg>
        </button>
        <button
          @click="editor?.chain().focus().toggleCode().run()"
          :class="{ 'is-active': editor?.isActive('code') }"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="24px"
            viewBox="0 -960 960 960"
            width="24px"
            fill="#5f6368"
          >
            <path
              d="M320-240 80-480l240-240 57 57-184 184 183 183-56 56Zm320 0-57-57 184-184-183-183 56-56 240 240-240 240Z"
            />
          </svg>
        </button>
        <button
          @click="editor?.chain().focus().toggleCodeBlock().run()"
          :class="{ 'is-active': editor?.isActive('codeBlock') }"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="24px"
            viewBox="0 -960 960 960"
            width="24px"
            fill="#5f6368"
          >
            <path
              d="m384-336 56-57-87-87 87-87-56-57-144 144 144 144Zm192 0 144-144-144-144-56 57 87 87-87 87 56 57ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z"
            />
          </svg>
        </button>
      </div>
      <div class="actions flex justify-end items-center">
        <button
          class="cancel btn btn-accent cursor-pointer mr-2"
          @mousedown.prevent
          @click="cancel"
        >
          Cancel
        </button>
        <button
          class="submit btn bg-secondary text-secondary-content cursor-pointer"
          @mousedown.prevent
          @click="submit"
        >
          Comment
        </button>
      </div>
    </div>
  </div>
  <p class="error-message text-sm text-error mt-2 mb-4" v-if="errorMessage">{{ errorMessage }}</p>
</template>

<script lang="ts" setup>
import { inject, onMounted, ref } from "vue"
import { EditorContent, useEditor } from "@tiptap/vue-3"
import { CodeBlock } from "@tiptap/extension-code-block"
import { Bold } from "@tiptap/extension-bold"
import { Code } from "@tiptap/extension-code"
import { HorizontalRule } from "@tiptap/extension-horizontal-rule"
import { Italic } from "@tiptap/extension-italic"
import { Text } from "@tiptap/extension-text"
import { Paragraph } from "@tiptap/extension-paragraph"
import { Document } from "@tiptap/extension-document"
import { Placeholder } from "@tiptap/extension-placeholder"
import HardBreak from "@tiptap/extension-hard-break"
import { CommentItem } from "./Comment.vue"
import Rating from "../Rating/Rating.vue"

const props = withDefaults(
  defineProps<{
    commentId?: number
    focus?: boolean
  }>(),
  {
    focus: false
  }
)

const isFocus = ref(props.focus)
const errorMessage = ref<string | null>(null)

const editor = useEditor({
  extensions: [
    Document,
    CodeBlock,
    Code,
    Text,
    Paragraph,
    Bold,
    Italic,
    HorizontalRule,
    HardBreak,
    Placeholder.configure({
      placeholder: "add a comment..."
    })
  ],
  editorProps: {
    attributes: {
      class: "prose prose-md m-5 focus:outline-none max-w-none"
    }
  },
  onFocus: () => (isFocus.value = true),
  onBlur: () => (isFocus.value = false),
  onUpdate: () => errorMessage.value && (errorMessage.value = null)
})

const emits = defineEmits<{
  (event: "cancel"): void
  (event: "added", item: CommentItem): void
}>()

onMounted(() => {
  if (props.focus) {
    editor?.value?.commands.focus()
  }
})

const submitComment = inject<(content: string, id: number | null) => Promise<any>>("submitComment")!

const submit = () => {
  submitComment(editor?.value?.getHTML() ?? "", props.commentId ?? null)
    .then((comment: CommentItem) => {
      editor.value?.commands.setContent("")
      editor.value?.commands.blur()

      emits("added", comment)
    })
    .catch((error) => {
      errorMessage.value = error.response?.data?.message ?? error.message
      console.error(error)
    })
}

const cancel = () => {
  errorMessage.value = null
  editor.value?.commands.setContent("")
  editor.value?.commands.blur()
  emits("cancel")
}
</script>
