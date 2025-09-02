<template>
  <div class="card">
    <comment v-for="item in comments.data" :data="item" :key="item.id" />
    <div class="load-more" v-if="comments.count > comments.data.length" @click="loadMore">
      <div class="icon">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368">
          <path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z" />
        </svg>
      </div>
      <span>Load More Comments</span>
    </div>
    <h2 class="mt-8" id="addReview">Write Your Review!</h2>
    <Input @added="comments.data.unshift($event)" />
  </div>
</template>

<script lang="ts" setup>
import "./comments.css"
import Input from "./Input.vue"
import Comment from "./Comment.vue"
import type { CommentItem } from "./Comment.vue"
import { onMounted, provide, reactive } from "vue"
import axios, { AxiosError } from "axios"

interface Comments {
  count: number
  data: CommentItem[]
}

const props = defineProps<{
  type: string
  id: number | string
}>()

const comments: Comments = reactive<Comments>({
  data: [],
  count: 0
})

onMounted(() => requestComments())

const requestComments = () => {
  void axios
    .request<Comments>({
      url: "/api/comments",
      method: "get",
      params: {
        commentable_type: props.type,
        commentable_id: props.id,
        offset: comments.data.length
      }
    })
    .then(({ data }) => {
      comments.data = comments.data.concat(data.data)
      comments.count = data.count
    })
}

const submitComment = (content: string, id: number | null = null) => {
  return new Promise((resolve, reject) => {
    axios
      .request<CommentItem>({
        method: "POST",
        url: "/api/comments",
        data: {
          content: content,
          commentable_type: props.type,
          commentable_id: props.id,
          comment_id: id
        }
      })
      .then(({ data }) => {
        resolve(data)
      })
      .catch((error: AxiosError) => {
        reject(error)
      })
  })
}

const loadMore = () => requestComments()

provide("submitComment", submitComment)
</script>
