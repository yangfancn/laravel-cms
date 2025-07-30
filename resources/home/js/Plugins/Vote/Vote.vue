<template>
  <div class="vote-box border border-accent rounded-full join">
    <button
      class="up btn join-item rounded-l-full"
      @click="handleClick(true)"
      :class="{ 'btn-active': _currentUserVote }"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        height="24px"
        viewBox="0 -960 960 960"
        width="24px"
        fill="#5f6368"
      >
        <path
          d="M320-120v-320H120l360-440 360 440H640v320H320Zm80-80h160v-320h111L480-754 289-520h111v320Zm80-320Z"
        />
      </svg>
      <span>{{ _up }}</span>
    </button>
    <div
      v-if="_down !== null"
      class="down btn join-item rounded-r-full"
      @click="handleClick(false)"
      :class="{ 'btn-active': _currentUserVote === false }"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        height="24px"
        viewBox="0 -960 960 960"
        width="24px"
        fill="#5f6368"
      >
        <path
          d="M320-120v-320H120l360-440 360 440H640v320H320Zm80-80h160v-320h111L480-754 289-520h111v320Zm80-320Z"
        />
      </svg>
      <span>{{ _down }}</span>
    </div>
  </div>
</template>

<script lang="ts" setup>
import "./vote.css"
import axios from "axios"
import { ref } from "vue"

const props = withDefaults(
  defineProps<{
    up: number
    down?: number
    type: string
    id: number
    currentUserVote: boolean | null
  }>(),
  {
    currentUserVote: null,
    down: 0
  }
)

const _up = ref(props.up)
const _down = ref(props.down)
const _currentUserVote = ref(props.currentUserVote)

const handleClick = (vote: boolean) => {
  axios
    .request<{
      vote: boolean | null
    }>({
      url: `/api/vote`,
      method: "POST",
      data: {
        vote: vote,
        votable_type: props.type,
        votable_id: props.id
      }
    })
    .then(({ data }) => {
      if (data.vote === null) {
        // 撤销投票的逻辑
        if (_currentUserVote.value === true) _up.value -= 1
        if (_currentUserVote.value === false) _down.value -= 1
      } else {
        // 更新投票状态的逻辑
        if (_currentUserVote.value === true) _up.value -= 1
        if (_currentUserVote.value === false) _down.value -= 1

        if (data.vote) _up.value += 1
        if (!data.vote) _down.value += 1
      }

      _currentUserVote.value = data.vote
    })
}
</script>
