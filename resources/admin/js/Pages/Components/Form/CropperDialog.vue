<template>
  <q-dialog v-model="showDialog" :persistent="true" transition-show="fade">
    <q-card class="cropper-box">
      <div ref="container" />
      <q-card-actions align="center" class="q-py-md">
        <q-btn color="primary" round icon="close" size=".75rem" @click="cancelCropper"></q-btn>
        <q-btn
          color="primary"
          round
          icon="undo"
          size=".75rem"
          @click="cropper!.getCropperImage()!.$rotate(-90)"
        ></q-btn>
        <q-btn
          color="primary"
          round
          icon="redo"
          size=".75rem"
          @click="cropper!.getCropperImage()!.$rotate(90)"
        ></q-btn>
        <q-btn color="primary" round icon="check" size=".75rem" @click="onCropped"></q-btn>
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script lang="ts" setup>
import { nextTick, ref, watch } from "vue"
import Cropper from "cropperjs"

interface Props {
  img: File | null
  aspectRatio?: number
}

const emit = defineEmits<{
  (e: "on-cropperd", blob: Blob | null): void
  (e: "on-cacnel"): void
}>()

const props = defineProps<Props>()
const showDialog = ref(false)
const cropper = ref<Cropper | null>()
const container = ref<HTMLElement>()

watch(props, async (value) => {
  if (value.img) {
    showDialog.value = true

    await nextTick()

    const image = new Image()
    image.src = await fileToBase64(value.img)

    image.onload = () => {
      cropper.value = new Cropper(image, {
        container: container.value
      })

      const cropperCanvas = cropper.value.getCropperCanvas()!
      cropperCanvas.style.height = cropperCanvas.clientWidth * (image.height / image.width) + "px"

      if (props.aspectRatio) {
        cropper.value.getCropperSelection()!.aspectRatio = props.aspectRatio
      }
    }
  }
})

function onCropped() {
  cropper
    .value!.getCropperSelection()!
    .$toCanvas()
    .then((canvas) => {
      canvas.toBlob((blob) => {
        emit("on-cropperd", blob)
      }, props.img!.type)
      closeDialog()
    }).catch(e => console.error(e))
}

function closeDialog() {
  showDialog.value = false
  cropper.value?.element.removeAttribute("src")
}

function cancelCropper() {
  closeDialog()

  emit("on-cacnel")
}

function fileToBase64(file: File): Promise<string> {
  return new Promise<string>((resolve, reject) => {
    const reader = new FileReader()
    reader.readAsDataURL(file)
    reader.onload = () => resolve(reader.result as string)
    reader.onerror = () => {
      const reason = reader.error ?? new Error("Failed to read file")
      reject(reason)
    }
  })
}
</script>

<style scoped>
.cropper-box {
  max-width: 100%;
  position: relative;
  overflow: hidden;

  :deep(cropper-canvas) {
    width: 650px;
    max-width: 100%;
    max-height: 100%;
  }

  .q-card__actions {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
  }
}
</style>
