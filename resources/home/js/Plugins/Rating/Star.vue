<template>
  <svg :viewBox="`0 0 ${size} ${size}`" :width="size" :height="size">
    <linearGradient :id="randomId" x1="0" x2="100%" y1="0" y2="0">
      <stop :offset="fill" :stop-color="activeColor" stop-opacity="1"></stop>
      <stop :offset="fill" :stop-color="inactiveColor" stop-opacity="1"></stop>
    </linearGradient>
    <polygon
      :points="points.join(',')"
      :fill="`url(#${randomId})`"
      :stroke="borderColor"
      :stroke-width="borderWidth"
      stroke-linejoin="round"
      stroke-linecap="round"
    />
    <polygon :points="points.join(',')" :fill="`url(#${randomId})`" />
  </svg>
</template>

<script setup lang="ts">
import { onMounted, ref } from "vue"

const props = withDefaults(
  defineProps<{
    fill?: number
    inactiveColor?: string
    activeColor?: string
    size: number
    borderWidth: number
    borderColor: string
  }>(),
  {
    fill: 0
  }
)

const defaultPoints = [19.8, 2.2, 6.6, 43.56, 39.6, 17.16, 0, 17.16, 33, 43.56]

const maxSize = defaultPoints.reduce((a, b) => Math.max(a, b))
const randomId = Math.random().toString(36).substring(7)

const points = ref<number[]>([])

onMounted(() => {
  defaultPoints.forEach((point, index) => {
    const offset = index % 2 === 0 ? 1.5 : 0
    points.value.push((props.size / maxSize) * point + offset)
  })
})
</script>
