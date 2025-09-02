<template>
  <div :class="{ comment: true, hasChildren: children_count, active: treeLineActive }" class="mb-5">
    <div class="info">
      <span class="avatar" :style="{ backgroundImage: `url('${data.user.photo ?? ''}')` }">{{ avatarName }}</span>
      <span class="name">{{ data.user.name }}</span>
      <span class="time">{{ data.created_at }}</span>
    </div>
    <div class="content">
      <div class="text prose prose-md max-w-none" v-html="data.content"></div>
      <div class="mt-4 flex items-center">
        <vote
          :id="data.id"
          type="comment"
          :up="data.up_votes"
          :down="data.down_votes"
          :current-user-vote="data.current_user_vote"
        />
        <button class="btn btn-neutral btn-sm ml-4 rounded-full" @click="inputting = !inputting">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="24px"
            viewBox="0 -960 960 960"
            width="18px"
            fill="oklch(0.985 0 0)"
          >
            <path
              d="M320-520q17 0 28.5-11.5T360-560q0-17-11.5-28.5T320-600q-17 0-28.5 11.5T280-560q0 17 11.5 28.5T320-520Zm160 0q17 0 28.5-11.5T520-560q0-17-11.5-28.5T480-600q-17 0-28.5 11.5T440-560q0 17 11.5 28.5T480-520Zm160 0q17 0 28.5-11.5T680-560q0-17-11.5-28.5T640-600q-17 0-28.5 11.5T600-560q0 17 11.5 28.5T640-520ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z"
            />
          </svg>
          <span>Reply</span>
        </button>
      </div>
      <div class="replay" v-if="inputting">
        <Input :comment-id="data.id" :focus="true" @cancel="inputting = false" @added="onAdded" />
      </div>
    </div>
    <div class="children" v-if="children_count">
      <div class="vertical-line-box" @mouseenter="lineActive" @mouseleave="lineInactive"></div>
      <comment v-for="item in children" :data="item" :parent="data" :key="item.id" />
      <div class="show-more" v-if="children.length < children_count" @click="loadMore">
        <div class="line" @mouseenter="lineActive" @mouseleave="lineInactive"></div>
        <div class="text">
          <div class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368">
              <path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z" />
            </svg>
          </div>
          <span>show more</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref } from "vue"
import axios from "axios"
import Input from "./Input.vue"
import Vote from "../Vote/Vote.vue"

export interface CommentItem {
  id: number
  content: string
  created_at: string
  user: {
    photo: string | null
    name: string
  }
  children?: CommentItem[]
  children_count: number | null
  up_votes: number
  down_votes: number
  current_user_vote: boolean | null
}

const props = defineProps<{
  data: CommentItem
  parent?: CommentItem
  depth?: number
}>()

const avatarName = props.data.user.photo ? "" : props.data.user.name.substring(0, 1)

const inputting = ref(false)

const children = ref<CommentItem[]>(props.data.children ?? [])

const children_count = ref(props.data.children_count ?? 0)

const treeLineActive = ref(false)

const lineColor = ref<string | null>(null)

const lineActive = () => {
  treeLineActive.value = true
  lineColor.value = "#707070"
}

const lineInactive = () => {
  treeLineActive.value = false
  lineColor.value = null
}

const loadMore = () => {
  void axios
    .request<{
      data: CommentItem[]
    }>({
      url: `/api/comments/${props.data.id}`,
      method: "GET",
      params: {
        offset: children.value.length
      }
    })
    .then(({ data }) => {
      children.value = children.value.concat(data.data)
    })
}

const onAdded = (arg: CommentItem): void => {
  children.value.push(arg)
  children_count.value += 1
  inputting.value = false
}
</script>
