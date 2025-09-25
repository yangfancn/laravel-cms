<template>
  <q-dialog v-model="showDialog" :persistent="true" transition-show="fade">
    <q-card class="cropper-box">
      <img v-if="imgSrc" :src="imgSrc" alt="crop image" ref="image" />
      <q-card-actions align="center" class="q-py-md">
        <q-btn color="primary" round icon="close" size=".75rem" @click="cancelCropper"></q-btn>
        <q-btn color="primary" round icon="undo" size=".75rem" @click="cropper!.rotate(-90)"></q-btn>
        <q-btn color="primary" round icon="redo" size=".75rem" @click="cropper!.rotate(90)"></q-btn>
        <q-btn color="primary" round icon="check" size=".75rem" @click="onCropped"></q-btn>
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script lang="ts" setup>
import { nextTick, ref, watch } from "vue"
import Cropper from "cropperjs"
import "cropperjs/dist/cropper.min.css"

interface Props {
  img: File | null
  aspectRatio?: number
}

const emit = defineEmits<{
  (e: "on-cropped", blob: Blob | null): void
  (e: "on-cancel"): void
}>()

const props = defineProps<Props>()
const showDialog = ref(false)
const cropper = ref<Cropper | null>()
const imgSrc = ref<string | null>()
const image = ref<HTMLImageElement | null>()

watch(props, async (value) => {
  if (value.img) {
    imgSrc.value = await fileToBase64(value.img)
    showDialog.value = true

    await nextTick()

    cropper.value = new Cropper(image.value!, {
      aspectRatio: props.aspectRatio,
      viewMode: ["image/png", "image/webp"].includes(props.img!.type) ? 0 : 1
    })
  }
})

function onCropped() {
  const imageData = cropper.value!.getImageData()
  cropper
    .value!.getCroppedCanvas({
      width: (imageData.width * imageData.naturalWidth) / cropper.value!.getContainerData().width
    })
    .toBlob((blob) => {
      emit("on-cropped", blob)
      closeDialog()
    }, props.img!.type)
}

function closeDialog() {
  showDialog.value = false
  cropper.value?.destroy()
}

function cancelCropper() {
  closeDialog()

  emit("on-cancel")
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

  .q-card__actions {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
  }
}
</style>
