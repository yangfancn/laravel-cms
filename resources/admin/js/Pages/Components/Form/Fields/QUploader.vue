<!-- @todo 目前 quasar 版本下的 uploader 还没办法实现默认展示已上传文件 如果后续有更新再尝试 -->
<!--<template>-->
<!--  <div>-->
<!--    <q-uploader-->
<!--      class="uploader"-->
<!--      v-bind="props"-->
<!--      :auto-upload="!(typeof aspectRatio !== 'undefined' && aspectRatio.length > 0)"-->
<!--      :headers="mergeHeader"-->
<!--      @rejected="onRejected"-->
<!--      @failed="onFailed"-->
<!--      @uploaded="onUploaded"-->
<!--      @removed="onRemoved"-->
<!--      @added="onAdded"-->
<!--      ref="uploader"-->
<!--    ></q-uploader>-->

<!--    <q-dialog v-model="showDialog" ref="dialog">-->
<!--      <q-card>-->
<!--        <div class="cropper-box" ref="cropperBox" :style="{height: `${cropperBoxHeight}px`}">-->
<!--          <vueCropper-->
<!--            ref="cropper"-->
<!--            :img="base64Img"-->
<!--            :auto-crop="true"-->
<!--            :fixed="typeof aspectRatio !== 'undefined' && aspectRatio.length > 0"-->
<!--            :fixed-number="aspectRatio"-->
<!--            :output-type="cropperOutputType"-->
<!--          ></vueCropper>-->
<!--        </div>-->
<!--        <q-card-actions align="center">-->
<!--          <q-btn color="primary" round icon="undo" size=".75rem" @click="cropper.rotateLeft()"></q-btn>-->
<!--          <q-btn color="primary" round icon="redo" size=".75rem" @click="cropper.rotateRight()"></q-btn>-->
<!--          <q-btn color="primary" round icon="check" size=".75rem" @click="onCropperFinish"></q-btn>-->
<!--        </q-card-actions>-->
<!--      </q-card>-->
<!--    </q-dialog>-->
<!--  </div>-->
<!--</template>-->

<script setup lang="ts">
// import {QDialog, QRejectedEntry, QUploader, QUploaderProps, QUploaderFactoryFn} from 'quasar'
// import {useQuasar, Cookies} from 'quasar'
// import {ref, watch, onMounted} from 'vue'
// import {VueCropper} from "vue-cropper";
//
// interface Props extends QUploaderProps {
//   cropper?: boolean
//   aspectRatio?: Array<number>
//   modelValue: Array<String>
//   maxFileSize?: number
//   maxTotalSize?: number
// }
//
// interface CustomFile extends File {
//   __key: string
//   __status: string
// }
//
// // Define reactive variables
// const props = defineProps<Props>()
// const $q = useQuasar()
// const uploader = ref<QUploader | null>(null)
// const cropper = ref<typeof VueCropper | null>(null)
// const cropperBox = ref<HTMLElement | null>(null)
// const cropperBoxHeight = ref<number>(0)
// const cropperOutputType = ref<string>('png')
// const emit = defineEmits(['update:modelValue'])
// const remoteFiles = ref<{ key: string, path: string }[]>([])
// const showDialog = ref(false)
// const base64Img = ref<string>()
//
// const processingFile = ref<{ current: File | null, queue: File[] }>({
//   current: null,
//   queue: [],
// })
//
// const croppedFile = ref<string[]>([])
// const allowedTypes = ['image/jpeg', 'image/png', 'image/webp']
//
// // Initialize remote files and sync props.modelValue changes
// onMounted(async () => {
//   if (props.modelValue) {
//     for (const path of props.modelValue) {
//       try {
//         const response = await fetch(path as string)
//         const blob = await response.blob()
//         const filename = path.split('/').pop()
//         const file = new File([blob], filename || 'file', {type: blob.type}) as CustomFile
//         croppedFile.value.push(filename ? filename : 'cropped')
//
//         // Add valid files to uploader
//         uploader.value?.addFiles([file])
//
//         uploader.value?.updateFileStatus(file, 'uploaded', file.size)
//
//         uploader.value?.$emit('uploaded', {
//           files: [file], xhr: {responseText: JSON.stringify({path: path})}
//         })
//       } catch (error) {
//         console.error(`Error fetching or creating file from ${path}:`, error)
//         return null
//       }
//     }
//
//     uploader.value?.removeQueuedFiles()
//     setTimeout(() => {
//       console.log(uploader.value)
//     }, 1000)
//   }
// })
//
// const factory = (files: CustomFile[]): QUploaderFactoryFn => {
//   const file = files[0];
//   if (file.__status === 'uploaded') {
//     return
//   }
//   return new Promise((resolve) => resolve({}))
// }
//
// // Define headers for uploader
// const mergeHeader = [
//   {
//     name: "X-XSRF-TOKEN",
//     value: Cookies.get('XSRF-TOKEN') ?? ''
//   },
//   {
//     name: "PLUGIN",
//     value: "QUploader"
//   }
// ]
//
// // Handle rejected files
// const onRejected = (rejectedEntries: QRejectedEntry[]) => {
//   rejectedEntries.forEach((item) => {
//     let message = ''
//
//     switch (item.failedPropValidation) {
//       case "max-file-size":
//         message = 'Out of limit size: ' + ((props.maxFileSize ?? 0) / 1024 / 1024).toFixed(2) + 'MB'
//         break
//       case "accept":
//         message = `File type allow ${props.accept}`
//         break
//       case "duplicate":
//         message = "Duplicate files"
//         break
//       case "max-total-size":
//         message = "Files total size out of limit: " + ((props.maxTotalSize ?? 0) / 1024 / 1024).toFixed(2) + "MB"
//         break
//       case "max-files":
//         message = "Files length out of limit: " + props.maxFiles
//         break
//     }
//
//     $q.notify({
//       type: "negative",
//       position: "bottom",
//       message: message
//     })
//   })
// }
//
// // Handle upload failed
// const onFailed = (info: { files: readonly any[], xhr: XMLHttpRequest }) => {
//   $q.notify({
//     type: "negative",
//     position: "bottom",
//     message: info.xhr.status + ' ' + info.xhr.statusText
//   })
// }
//
// // Handle uploaded files
// interface UploadedInfo {
//   files: readonly CustomFile[],
//   xhr: XMLHttpRequest
// }
//
// const onUploaded = (info: UploadedInfo) => {
//   const response = JSON.parse(info.xhr.responseText)
//   info.files.forEach((file) => {
//     remoteFiles.value.push({
//       key: file.__key,
//       path: response?.path
//     })
//   })
// }
//
// // Handle removed files
// const onRemoved = (files: readonly CustomFile[]) => {
//   files.forEach(file => {
//     remoteFiles.value = remoteFiles.value.filter(item => item.key !== file.__key)
//   })
// }
//
// // Finish cropper action
// const onCropperFinish = () => {
//   cropper?.value?.getCropBlob((data: Blob) => {
//     const file = new File([data], processingFile.value.current?.name || 'cropped_image.png', {type: processingFile.value.current?.type})
//     croppedFile.value.push(file.name)
//     showDialog.value = false
//     uploader.value?.addFiles([file])
//     uploader.value?.upload()
//   })
// }
//
// // Process next file in queue
// const processNextFile = async () => {
//   const file = processingFile.value.queue.shift() as File | undefined
//
//   if (file) {
//     processingFile.value.current = file
//
//     try {
//       const data = await fileToBase64(file)
//       const image = new Image()
//       image.src = URL.createObjectURL(file)
//       image.onload = () => {
//         cropperBoxHeight.value = cropperBox.value!.clientWidth / (image.width / image.height)
//       }
//
//       base64Img.value = data
//       cropperOutputType.value = file.type.split('/')[1] // 设置输出类型与输入类型一致
//       showDialog.value = true
//     } catch (error) {
//       $q.notify({
//         message: "初始化图片裁剪器失败，请查看控制台",
//         type: "negative",
//         position: "bottom"
//       })
//       console.error(error)
//     }
//   }
// }
//
// // Add files handler
// const onAdded = async (files: readonly File[]) => {
//   files.filter(file => allowedTypes.includes(file.type) && props.cropper)
//     .forEach(file => {
//       //@todo file.name 可能重复
//       const index = croppedFile.value.indexOf(file.name)
//       if (index === -1) {
//         uploader.value?.removeFile(file)
//         processingFile.value.queue.push(file)
//       }
//     })
//   await processNextFile()
// }
//
// // Watch remoteFiles changes and emit update:modelValue
// watch(remoteFiles, (newFiles) => {
//   emit('update:modelValue', newFiles.map(item => item.path))
// }, { deep: true })
//
// // Convert file to base64
// const fileToBase64 = (file: File) =>
//   new Promise<string>((resolve, reject) => {
//     const reader = new FileReader()
//     reader.readAsDataURL(file)
//     reader.onload = () => resolve(reader.result as string)
//     reader.onerror = (error) => reject(error)
//   })
</script>

<style scoped>
.uploader {
  width: 100%;
  max-width: 100%;
}

.cropper-box {
  width: 600px;
  max-width: 100%;
  overflow: hidden;
}
</style>
