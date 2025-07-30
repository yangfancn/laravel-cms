<template>
  <div class="left-item">
    <q-icon :name="icon" />
    <h3>{{ title }}</h3>
    <strong>{{ newCount }}</strong>
    <div>
      <span>{{ changePercent ? `${changePercent.toFixed(2)}%` : "-" }}</span>
      <q-icon v-if="changePercent && changePercent > 0" name="mdi_arrow_upward_alt" color="green" />
      <q-icon
        v-else-if="changePercent && changePercent < 0"
        name="mdi_arrow_upward_alt"
        color="red"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  title: string
  newCount: number
  changePercent: number | null
  icon: string
}>()
</script>

<style scoped>
.left-item {
  border-radius: 12px;
  padding: 16px;
  background: #cdcdcd1f;
  display: flex;
  flex-flow: row wrap;
  justify-content: space-between;
  align-items: center;

  h3 {
    font-size: 18px;
    line-height: 142%;
    font-weight: 600;
    margin: 1rem 0;
    flex: 100%;
  }

  strong {
    font-size: 24px;
    flex: 0 0 auto;
  }

  > i {
    font-size: 42px;
    width: fit-content;
    color: #009688;
    flex: 100%;
    justify-content: flex-start;
  }

  > div {
    i {
      margin-left: 5px;
    }
  }
}
</style>
