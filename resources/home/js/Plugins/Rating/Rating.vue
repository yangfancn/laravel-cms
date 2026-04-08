<template>
  <div :class="{ 'star-rating': true, disabled: disable }">
    <div
      class="star"
      v-for="n in max"
      :key="n"
      @mousemove="disable ? null : mouseMove($event, n)"
      @click="disable ? null : click($event, n)"
    >
      <star
        :fill="fills[n - 1]"
        :active-color="activeColor"
        :inactiveColor="inactiveColor"
        :size="size"
        :border-color="borderColor"
        :border-width="borderWidth"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import "./rating.css"
import Star from "./Star.vue"
import { computed, ref } from "vue"

const props = withDefaults(
  defineProps<{
    modelValue?: number
    max?: number
    step?: number
    inactiveColor?: string
    activeColor?: string
    disable?: boolean
    size?: number
    borderWidth?: number
    borderColor?: string
  }>(),
  {
    modelValue: 0,
    max: 5,
    inactiveColor: "#ddd",
    activeColor: "#e0633e",
    disable: false,
    size: 24,
    step: 0.01,
    borderWidth: 1.5,
    borderColor: "#c84d29"
  }
)

const emits = defineEmits(["update:modelValue"])

// const fills = ref<number[]>([]);
const rating = ref(props.modelValue)
const isRating = ref(!props.disable)

const fills = computed(() => {
  let arr: number[] = []
  for (let i = 0; i < props.max; i++) {
    let fill = 0
    if (i < rating.value) {
      fill = rating.value - i > 1 ? 1 : parseFloat((rating.value - i).toFixed(2))
    }
    arr.push(fill)
  }
  return arr
})

const mouseMove = (evt: MouseEvent, index: number) => {
  if (!props.disable && isRating.value) {
    const rawRating = index - 1 + evt.offsetX / props.size
    // 应用 step 将评分值调整为 step 的倍数
    const steppedRating = Math.round(rawRating / props.step) * props.step
    rating.value = Math.min(Math.max(steppedRating, 0), props.max)

    emits("update:modelValue", parseFloat(rating.value.toFixed(2)))
  }
}

const click = (evt: MouseEvent, index: number) => {
  const rawIsRating = isRating.value
  isRating.value = true
  mouseMove(evt, index)
  isRating.value = !rawIsRating
}
</script>
