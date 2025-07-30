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
  pageViews: DashboardPageVisits[]
  uniqueIpVisitors: DashboardUniqueVisitors[]
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
const mergeData = (() => {
  const allDates = Array.from(
    new Set([
      ...props.pageViews.map((item) => item.visit_date),
      ...props.uniqueIpVisitors.map((item) => item.visit_date)
    ])
  ).sort()

  return {
    date: allDates,
    pageViews: allDates.map((date) => {
      const match = props.pageViews.find((item) => item.visit_date === date)
      return match ? match.page_views : 0 // 如果没有数据，填充为 0
    }),
    uniqueVisitors: allDates.map((date) => {
      const match = props.uniqueIpVisitors.find((item) => item.visit_date === date)
      return match ? match.unique_ip_views : 0 // 如果没有数据，填充为 0
    })
  }
})()

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
      data: mergeData.date,
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
  series: [
    {
      name: "Page Views",
      type: "line",
      emphasis: {
        focus: "series"
      },
      data: mergeData.pageViews,
      smooth: true,
      symbol: "none"
    },
    {
      name: "Unique IP Views",
      type: "line",
      emphasis: {
        focus: "series"
      },
      data: mergeData.uniqueVisitors,
      smooth: true,
      symbol: "none"
    }
  ]
}
</script>

<style scoped></style>
