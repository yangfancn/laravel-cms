<template>
  <div class="box">
    <v-chart
      :option="option"
      :theme="$q.dark.isActive ? 'dark' : 'light'"
      autoresize
      style="width: 100%; height: 100%"
    />
  </div>
</template>

<script setup lang="ts">
import { CanvasRenderer } from "echarts/renderers"
import { use } from "echarts/core"
import { LineChart } from "echarts/charts"
import {
  GridComponent,
  LegendComponent,
  TitleComponent,
  ToolboxComponent,
  TooltipComponent
} from "echarts/components"
import { useQuasar } from "quasar"
import VChart from "vue-echarts"
import dayjs from "dayjs"

const $q = useQuasar()
const props = defineProps<{
  data: DashboardBot[]
}>()

// 注册需要的组件和地图
use([
  CanvasRenderer,
  LineChart,
  TitleComponent,
  TooltipComponent,
  LegendComponent,
  ToolboxComponent,
  GridComponent
])

// 数据处理逻辑
const calcData = props.data.reduce(
  (acc, bot) => {
    acc.date.push(bot.created_at)
    acc.data.baidu.push(bot.baidu)
    acc.data.bing.push(bot.bing)
    acc.data.duckduckgo.push(bot.duckduckgo)
    acc.data.google.push(bot.google)
    acc.data.yandex.push(bot.yandex)
    acc.data.other.push(bot.other)
    return acc
  },
  {
    date: [] as string[],
    data: {
      baidu: [] as number[],
      bing: [] as number[],
      duckduckgo: [] as number[],
      google: [] as number[],
      yandex: [] as number[],
      other: [] as number[]
    }
  }
)

const option = {
  tooltip: {
    trigger: "axis",
    axisPointer: {
      type: "cross"
    }
  },
  title: {
    text: "Last 30 days visitors",
    top: "5%",
    left: "center"
  },
  legend: {
    top: "15%"
  },
  toolbox: {
    feature: {
      saveAsImage: {}
    }
  },
  grid: {
    top: "25%",
    left: "3%",
    right: "4%",
    bottom: "3%",
    containLabel: true
  },
  xAxis: [
    {
      type: "category",
      boundaryGap: false,
      data: calcData.date,
      interval: 7,
      axisLabel: {
        formatter: (value: string) => {
          return dayjs(value).format("MM/DD")
        },
        interval: 7
      }
    }
  ],
  yAxis: [
    {
      type: "value",
      axisLabel: {
        showMinLabel: false
      }
    }
  ],
  series: Object.entries(calcData.data).map((item) => ({
    name: item[0],
    type: "line",
    emphasis: {
      focus: "series"
    },
    data: item[1],
    smooth: true,
    symbol: "none"
  }))
}
</script>

<style scoped></style>
