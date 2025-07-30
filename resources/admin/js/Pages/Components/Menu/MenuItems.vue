<template>
  <q-list>
    <template v-for="(item, index) in menu" :key="item.name">
      <template v-if="item.children && item.children.length > 0">
        <q-expansion-item
          v-ripple="false"
          :label="$t('menu.' + item.label)"
          :icon="item.icon"
          :model-value="expandedItems.includes(item.id)"
          @update:model-value="toggleExpansion(item.id, $event)"
          :style="{ '--icon-color': item.icon_color }"
        >
          <q-item-section>
            <MenuItems :menu="item.children" />
          </q-item-section>
        </q-expansion-item>
      </template>

      <q-item
        v-else
        clickable
        v-ripple
        @click="item.route && router.get($safeRoute(item.route, item.params ?? undefined))"
        :active="route().current() === item.route"
        active-class="bg-teal-1 text-grey-8"
      >
        <q-item-section avatar>
          <q-icon :name="item.icon" :style="{ color: item.icon_color }"></q-icon>
        </q-item-section>
        <q-item-section>
          <q-item-label>
            {{ $t("menu." + item.label) }}
          </q-item-label>
        </q-item-section>
      </q-item>
      <q-separator v-if="index !== menu.length - 1" />
    </template>
  </q-list>
</template>

<script lang="ts" setup>
import { router } from "@inertiajs/vue3"
import { onMounted, ref } from "vue"
import { route } from "ziggy-js"

const props = defineProps<{
  menu: MenuItem[]
}>()

const loadExpandedItems = (): number[] => {
  const storedItems = sessionStorage.getItem("expandedItems")
  if (storedItems) {
    return JSON.parse(storedItems)
  }
  return []
}

const expandedItems = ref<number[]>(loadExpandedItems())

const saveExpandedItems = () => {
  sessionStorage.setItem("expandedItems", JSON.stringify(expandedItems.value))
}

const toggleExpansion = (id: number, expanded: boolean) => {
  if (expanded) {
    if (!expandedItems.value.includes(id)) {
      expandedItems.value.push(id)
    }
  } else {
    expandedItems.value = expandedItems.value.filter((item) => item !== id)
  }
  saveExpandedItems()
}

onMounted(() => {})
</script>

<style>
.q-expansion-item {
  --icon-color: #666;
}

.q-expansion-item__content {
  padding-left: 1rem;
}

.q-item__section--avatar {
  min-width: unset;

  .q-icon {
    color: var(--icon-color);
  }
}

.q-item__label a {
  text-decoration: none;
  color: inherit;
}

/* .menu-item-active {
  background: #e1e1e1;
  color: #000;
} */

/* .q-scrollarea__content {
  > .q-list {
    > .q-item,
    > .q-expansion-item {
    }
  }
} */
</style>
