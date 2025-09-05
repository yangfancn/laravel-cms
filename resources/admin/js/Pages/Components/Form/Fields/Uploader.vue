<template>
  <q-field :error="error" :error-message="errorMessage" :label="label" standout :bg-color="dark ? '' : 'grey-2'">
    <file-pond
      :class="[allowMultiple ? 'multi' : 'single', 'q-field__native q-placeholder']"
      ref="uploader"
      :server="server"
      :allowPaste="false"
      :instantUpload="true"
      v-bind="props"
      v-bind:files="files"
      @reorderfiles="onReorderFiles"
      @addfilestart="onAddFileStart"
      @processfile="onProcessFile"
      @removefile="onRemoveFile"
      :imagePreviewHeight="allowMultiple ? 200 : null"
    >
    </file-pond>
  </q-field>
  <cropper-dialog
    v-if="cropper"
    :img="cropperFile"
    :aspect-ratio="aspectRatio"
    @on-cancel="onCroppedCancel"
    @on-cropped="onCropped"
  />
</template>

<script lang="ts" setup>
import VueFilePond from "vue-filepond"
import type { FilePondFile, FilePondErrorDescription, FilePondServerConfigProps, FileStatus } from "filepond"
import FilePondPluginImagePreview from "filepond-plugin-image-preview"
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type"
import FIlePondPluginImageExifOrientation from "filepond-plugin-image-exif-orientation"
import { useQuasar } from "quasar"
import { onBeforeUnmount, ref } from "vue"
import axios, { AxiosError } from "axios"
import { trans } from "laravel-vue-i18n"
import CropperDialog from "../CropperDialog.vue"
import { useFormContext } from "../useFormContext"

defineOptions({
  inheritAttrs: false
})

interface Props {
  acceptedFileTypes: string
  name: string
  label: string
  disabled: boolean
  allowMultiple: boolean
  allowReorder: true
  maxFiles: number
  modelValue?: string[] | string
  aspectRatio?: number
  cropper: boolean
  error?: boolean
  errorMessage?: string
  dark?: boolean
}

const FilePond = VueFilePond(
  FilePondPluginImagePreview,
  FilePondPluginFileValidateType,
  FIlePondPluginImageExifOrientation
)

const props = defineProps<Props>()
const files = (props.modelValue ? (Array.isArray(props.modelValue) ? props.modelValue : [props.modelValue]) : []).map(
  (source) => ({
    source,
    options: {
      type: "local"
    }
  })
)

const server: FilePondServerConfigProps["server"] = {
  process: (_filedName, file, _metadata, load, error, progress, abort) => {
    const formData = new FormData()
    formData.append("file", file, file.name)

    // Create a new CancelToken source for axios
    const CancelToken = axios.CancelToken
    const source = CancelToken.source()

    // Axios request configuration
    axios
      .request<UploadResponse>({
        method: "post",
        url: "/manager/upload",
        data: formData,
        cancelToken: source.token,
        onUploadProgress: (event) => {
          progress(event.lengthComputable, event.loaded, event.total!)
        }
      })
      .then((response) => {
        load(response.data.url!)
      })
      .catch((thrown: AxiosError<UploadResponse>) => {
        console.log(thrown.response)
        const message = thrown.response?.data.message ?? "upload failed: unknown error"
        $q.notify({
          message: message,
          type: "negative"
        })
        error(message)
      })

    return {
      abort: () => {
        source.cancel("Upload canceled by user")
        abort()
      }
    }
  },
  revert: null,
  load: "/"
}

const $q = useQuasar()
// FilePond FileStatus enum isn't available at runtime; create typed constants
const FILE_STATUS_PROCESSING: FileStatus = 3 as FileStatus
const FILE_STATUS_PROCESSING_QUEUED: FileStatus = 9 as FileStatus
interface FilePondComponent {
  getFiles: () => FilePondFile[]
  addFile: (file: Blob | File, options?: { index?: number }) => void
}
const uploader = ref<FilePondComponent | null>(null)
const cropperFile = ref<File | null>(null)
const processingFile = ref<{ current: File | null; queue: File[] }>({
  current: null,
  queue: []
})

const emit = defineEmits(["update:modelValue"])
const { addAllowSubmitHandler } = useFormContext()

const onAddFileStart = async (file: FilePondFile) => {
  if (!props.cropper || !file.fileType.startsWith("image/") || !(file.source instanceof File)) {
    return
  }

  // Check if image already matches desired aspect ratio
  if (props.aspectRatio) {
    const img = new Image()
    img.src = URL.createObjectURL(file.source)

    await new Promise((resolve) => {
      img.onload = resolve
    })

    const imageAspectRatio = img.width / img.height
    const targetAspectRatio = props.aspectRatio
    const aspectRatioThreshold = 0.01 // Small tolerance for floating point comparison

    if (Math.abs(imageAspectRatio - targetAspectRatio) < aspectRatioThreshold) {
      URL.revokeObjectURL(img.src)

      return
    }

    URL.revokeObjectURL(img.src)
  }

  file.abortLoad()
  file.abortProcessing()

  processingFile.value.queue.push(file.source)

  if (!processingFile.value.current) {
    processNextFile()
  }
}

const onReorderFiles = (files: FilePondFile[]) => {
  emit(
    "update:modelValue",
    files.map((file) => file.serverId)
  )
}

const onProcessFile = (_error: FilePondErrorDescription | null, file: FilePondFile) => {
  emit(
    "update:modelValue",
    props.maxFiles === 1
      ? file.serverId
      : Array.isArray(props.modelValue)
        ? props.modelValue.concat([file.serverId]).filter((item) => item)
        : [file.serverId]
  )
}

const onRemoveFile = () => {
  const files = uploader.value!.getFiles().map((file: FilePondFile) => file.serverId)

  emit("update:modelValue", files.length === 0 ? null : files)
}

function onCropped(blob: Blob | null): void {
  if (blob) {
    uploader.value!.addFile(blob, {
      index: uploader.value?.getFiles().length
    })
  }
  cropperFile.value = null
  processNextFile()
}

function onCroppedCancel(): void {
  cropperFile.value = null
  processNextFile()
}

const processNextFile = () => {
  const file = processingFile.value.queue.shift() as File | undefined

  processingFile.value.current = file ?? null

  if (file) {
    try {
      cropperFile.value = file
    } catch (error) {
      $q.notify({
        message: trans("messages.cropperInitFailed"),
        type: "negative",
        position: "bottom"
      })
      console.error(error)
    }
  }
}

const removeHandler = addAllowSubmitHandler(() => {
  for (const file of uploader.value!.getFiles()) {
    if (file.status === FILE_STATUS_PROCESSING || file.status === FILE_STATUS_PROCESSING_QUEUED) {
      return false
    }
  }
  return true
})

onBeforeUnmount(() => {
  removeHandler()
})
</script>

<style scoped lang="scss">
.q-field--auto-height.q-field--labeled :deep(.q-field__control-container) {
  padding-top: 30px;
}

:deep(.q-field__append) {
  position: absolute;
  right: 12px;
}

.q-field--standout.q-field--dark.q-field--highlighted :deep(.q-field__control) {
  background: rgba(255, 255, 255, 0.07);
}

.q-field--dark :deep(.filepond--drop-label) {
  color: #bbbbbb;
}
</style>
