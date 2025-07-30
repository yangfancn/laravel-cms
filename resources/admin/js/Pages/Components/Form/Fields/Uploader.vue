<template>
  <q-field
    :error="error"
    :error-message="errorMessage"
    :label="label"
    standout
    :bg-color="dark ? '' : 'grey-2'"
  >
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
      :imagePreviewHeight="allowMultiple ? '200px' : null"
    >
    </file-pond>
  </q-field>
  <q-dialog v-model="showDialog" :persistent="true">
    <q-card class="cropper-box">
      <img v-if="imgSrc" ref="cropperImg" :src="imgSrc" alt="cropper" />
      <q-card-actions align="center" class="q-py-md">
        <q-btn color="primary" round icon="close" size=".75rem" @click="cancelCropper"></q-btn>
        <q-btn
          color="primary"
          round
          icon="undo"
          size=".75rem"
          @click="cropper!.rotate(-90)"
        ></q-btn>
        <q-btn color="primary" round icon="redo" size=".75rem" @click="cropper!.rotate(90)"></q-btn>
        <q-btn color="primary" round icon="check" size=".75rem" @click="onCropped"></q-btn>
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script lang="ts" setup>
import VueFilePond from "vue-filepond"
import { FilePondErrorDescription, FilePondFile, FilePondServerConfigProps } from "filepond"
import Cropper from "cropperjs"
import FilePondPluginImagePreview from "filepond-plugin-image-preview"
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type"
import FIlePondPluginImageExifOrientation from "filepond-plugin-image-exif-orientation"
import { useQuasar } from "quasar"
import { inject, nextTick, ref } from "vue"
import axios from "axios"
import { trans } from "laravel-vue-i18n"

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
const files = (
  props.modelValue ? (Array.isArray(props.modelValue) ? props.modelValue : [props.modelValue]) : []
).map((source) => ({
  source,
  options: {
    type: "local"
  }
}))

const server: FilePondServerConfigProps["server"] = {
  process: (filedName, file, metadata, load, error, progress, abort, transfer, options) => {
    const formData = new FormData()
    formData.append("file", file, file.name)

    // Create a new CancelToken source for axios
    const CancelToken = axios.CancelToken
    const source = CancelToken.source()

    // Axios request configuration
    axios
      .request({
        method: "post",
        url: "/manager/upload",
        data: formData,
        cancelToken: source.token,
        onUploadProgress: (event) => {
          progress(event.lengthComputable, event.loaded, event.total!)
        }
      })
      .then((response) => {
        load(response.data.url)
      })
      .catch((thrown) => {
        const message = thrown.message ?? "upload failed: unknown error"
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
const uploader = ref<typeof FilePond | null>(null)
const cropperImg = ref<HTMLImageElement>()
const showDialog = ref(false)
const imgSrc = ref<string | null>(null)
const cropper = ref<Cropper | null>(null)
const processingFile = ref<{ current: File | null; queue: File[] }>({
  current: null,
  queue: []
})

const emit = defineEmits(["update:modelValue"])
const addAllowSubmitHandler = inject("addAllowSubmitHandler") as (handler: () => boolean) => void

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
    await processNextFile()
  }
}

const onReorderFiles = (files: FilePondFile[]) => {
  emit(
    "update:modelValue",
    files.map((file) => file.serverId)
  )
}

const onProcessFile = (error: FilePondErrorDescription | null, file: FilePondFile) => {
  emit(
    "update:modelValue",
    props.maxFiles === 1
      ? file.serverId
      : Array.isArray(props.modelValue)
        ? props.modelValue.concat([file.serverId])
        : [file.serverId]
  )
}

const onRemoveFile = () => {
  const files = uploader.value!.getFiles().map((file: FilePondFile) => file.serverId)
  emit("update:modelValue", files.length === 0 ? null : files)
}

const onCropped = () => {
  cropper.value!.getCroppedCanvas().toBlob((blob) => {
    uploader.value!.addFile(blob, {
      index: uploader.value?.getFiles().length
    })
    closeCropper()
  }, processingFile.value.current!.type!)
}

const closeCropper = async () => {
  showDialog.value = false
  imgSrc.value = null

  cropper.value?.element.removeAttribute('src')

  await processNextFile()
}

const cancelCropper = () => {
  closeCropper()
}

const fileToBase64 = (file: File) =>
  new Promise<string>((resolve, reject) => {
    const reader = new FileReader()
    reader.readAsDataURL(file)
    reader.onload = () => resolve(reader.result as string)
    reader.onerror = (error) => reject(error)
  })

const processNextFile = async () => {
  const file = processingFile.value.queue.shift() as File | undefined

  processingFile.value.current = file ? file : null

  if (file) {
    try {
      imgSrc.value = await fileToBase64(file)
      showDialog.value = true

      await nextTick()

      cropper.value = new Cropper(cropperImg.value!, {
        viewMode: 1,
        aspectRatio: props.aspectRatio
      })
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

addAllowSubmitHandler(() => {
  for (const file of uploader.value?.getFiles()) {
    if (file.status === 3 || file.status === 9) {
      return false
    }
  }
  return true
})
</script>

<style scoped lang="scss">
.cropper-box {
  max-width: 100%;
  position: relative;
  overflow: hidden;
  padding-bottom: 60px;

  .q-card__actions {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
  }
}

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
