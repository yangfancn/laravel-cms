<template>
  <Layout title="Homepage">
    <div class="grid">
      <div class="item">
        <div class="row full-height">
          <div class="col-4 full-height">
            <div class="left-items">
              <new-data
                title="Today New Posts"
                :new-count="page.props.newPosts"
                :change-percent="page.props.newPostsChangePercent"
                icon="article"
              />
              <new-data
                title="Today New Users"
                :new-count="page.props.newUsers"
                :change-percent="page.props.newUsersChangePercent"
                icon="group"
              />
            </div>
          </div>
          <div class="col-8" style="padding-left: 16px">
            <div class="box">
              <q-carousel
                v-model="activeSlide"
                swipeable
                animated
                prev-icon="chevron_left"
                next-icon="chevron_right"
                padding
                arrows
                infinite
              >
                <q-carousel-slide :name="1">
                  <h3>Popular Pages</h3>
                  <q-markup-table :bordered="false">
                    <thead>
                      <tr>
                        <th>Path</th>
                        <th>Views</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="item in page.props.popularPages" :key="item.path">
                        <td>{{ item.path }}</td>
                        <td>{{ item.total_visits }}</td>
                      </tr>
                    </tbody>
                  </q-markup-table>
                </q-carousel-slide>
                <q-carousel-slide :name="2">
                  <h3>Frequently IP</h3>
                  <q-markup-table>
                    <thead>
                      <tr>
                        <th>IP</th>
                        <th>Country</th>
                        <th>Views</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="item in page.props.frequentlyIps" :key="item.ip">
                        <td>{{ item.ip }}</td>
                        <td>{{ item.country }}</td>
                        <td>{{ item.total_visits }}</td>
                      </tr>
                    </tbody>
                  </q-markup-table>
                </q-carousel-slide>
              </q-carousel>
            </div>
          </div>
        </div>
      </div>
      <div class="item">
        <visitors
          :page-views="page.props.pageVisits"
          :unique-ip-visitors="page.props.uniqueVisitors"
        />
      </div>
      <div class="item">
        <distribution :data="page.props.visitDistribution" />
      </div>
      <div class="item">
        <bots :data="page.props.bots" />
      </div>
    </div>
  </Layout>
</template>

<script setup lang="ts">
import Layout from "../Layout.vue"
import { usePage } from "@inertiajs/vue3"
import Distribution from "./Distribution.vue"
import { registerTheme } from "echarts/core"
import chalk from "../../jsons/echarts-theme-chalk.json"
import westEros from "../../jsons/echarts-theme-westeros.json"
import Visitors from "./Visitors.vue"
import Bots from "./Bots.vue"
import NewData from "./NewData.vue"
import { ref } from "vue"

const page = usePage<{
  bots: DashboardBot[]
  pageVisits: DashboardPageVisits[]
  uniqueVisitors: DashboardUniqueVisitors[]
  visitDistribution: DashboardVisitDistribution[]
  newPosts: number
  newPostsChangePercent: number | null
  newUsers: number
  newUsersChangePercent: number | null
  popularPages: {
    path: string
    total_visits: number
  }[]
  frequentlyIps: {
    ip: string
    country: string
    total_visits: number
  }[]
}>()
const activeSlide = ref(2)

registerTheme("dark", chalk)
registerTheme("light", westEros)
</script>

<style lang="scss" scoped>
.grid {
  width: 100%;
  height: var(--pageHeight);
  display: grid;
  grid-template-rows: repeat(2, 1fr);
  grid-template-columns: repeat(2, 1fr);
  gap: 16px 16px;
  overflow: hidden;

  .item {
    overflow: hidden;
  }
}

.box {
  border-radius: 12px;
  overflow: hidden;
  background-color: #cdcdcd1f;
  height: 100%;
}

.left-items {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  height: 100%;

  > * {
    flex: 0 0 calc(50% - 8px);
  }
}

.q-carousel {
  display: flex;
  flex-flow: row wrap;
  background: transparent;

  :deep(.q-carousel__slides-container) {
    flex: 0 0 100%;
    order: 2;
    height: fit-content;

    .q-carousel__slide {
      padding: 10px;

      h3 {
        font-size: 18px;
        line-height: 142%;
        font-weight: 600;
        margin: 0 0 1rem 0;
      }

      .q-markup-table {
        background-color: transparent;
        border: none;
        box-shadow: none;

        th {
          text-align: left;
          font-weight: 600;
        }

        td {
          text-align: left;
          font-weight: 400;
          color: #444;
        }
      }
    }
  }

  :deep(.q-carousel__prev-arrow) {
    position: static;
    order: 0;
    margin-left: auto;

    .q-icon {
      color: #333;
    }
  }

  :deep(.q-carousel__next-arrow) {
    position: static;
    order: 1;
    margin-left: 0;

    .q-icon {
      color: #333;
    }
  }
}
</style>
