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

<script lang="ts" setup>
import { CanvasRenderer } from "echarts/renderers"
import { registerMap, use } from "echarts/core"
import { EffectScatterChart, MapChart } from "echarts/charts"
import { GeoComponent, TitleComponent, TooltipComponent, VisualMapComponent } from "echarts/components"
import { shallowRef } from "vue"
import VChart from "vue-echarts"
import worldMap from "../../jsons/world.json"
import { useQuasar } from "quasar"
// import { GeoJSONSourceInput } from "echarts/types/src/coord/geo/geoTypes"
// import { GeoJSONSourceInput } from "echarts/types/src/coord/geo/geoTypes"
import type { GeoJSONSourceInput } from "echarts/types/src/coord/geo/geoTypes.js"

const props = defineProps<{
  data: DashboardVisitDistribution[]
}>()

const $q = useQuasar()

// 注册需要的组件和地图
use([CanvasRenderer, MapChart, EffectScatterChart, TitleComponent, TooltipComponent, VisualMapComponent, GeoComponent])

// 注册世界地图
registerMap("world", worldMap as GeoJSONSourceInput)

// 注册配色

// 示例数据
const data = props.data.map((item) => ({ name: item.country, value: item.count }))

// 图表配置项
const option = shallowRef({
  textStyle: {
    fontWeight: 500
  },
  darkMode: false,
  visualMap: {
    right: 0,
    min: 0,
    max: data.reduce((max, item) => (item.value > max ? item.value : max), -Infinity),
    calculable: true
  },

  title: {
    text: "Traffic Distribution",
    top: "5%",
    left: "center"
  },
  tooltip: {
    trigger: "item",
    showDelay: 0,
    transitionDuration: 0.2
  },
  geo: [
    {
      map: "world",
      roam: true,
      emphasis: {
        label: {
          show: true
        }
      },
      itemStyle: {
        // areaColor: "#323c48",
        // borderColor: "#111",
      },
      top: "20%",
      bottom: "0%"
    }
  ],
  series: [
    {
      name: "Visits",
      type: "map",
      map: "world",
      data: data,
      geoIndex: 0
    }
  ]
})
</script>

<style scoped></style>
