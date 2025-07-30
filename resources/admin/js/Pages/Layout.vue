<template>
  <Head :title="title" />
  <q-layout view="lHh Lpr lff">
    <q-header elevated :class="$q.dark.mode ? 'bg-dark' : 'bg-teal text-white'">
      <q-toolbar>
        <q-btn dense flat round icon="menu" @click="toggleDrawer" />
        <q-toolbar-title>
          <slot name="pageTitle"></slot>
        </q-toolbar-title>
        <q-btn flat round dense icon="brightness_6" @click="toggleDarkMode" />
        <q-item clickable :class="{ active: profileMenuOpen }">
          <q-item-section side>
            <q-avatar rounded>
              <img
                v-if="page.props.user?.photo"
                :src="page.props.user?.photo"
                :alt="page.props.user?.name"
              />
              <img v-else src="../../images/default-avatar.jpg" :alt="page.props.user?.name" />
            </q-avatar>
          </q-item-section>
          <q-item-section>
            <q-item-label>{{ page.props.user?.name }}</q-item-label>
            <q-item-label caption class="text-white">{{
              page.props.user?.roles[0].name
            }}</q-item-label>
          </q-item-section>
          <q-menu auto-close square fit v-model="profileMenuOpen" class="user-avatar bg-teal-4">
            <q-list>
              <q-item>
                <q-item-section>
                  <Link :href="safeRoute('logout')" method="delete" class="logout">Logout</Link>
                </q-item-section>
              </q-item>
              <q-separator />
              <q-item>
                <q-item-section>
                  <Link :href="safeRoute('admin.users.edit', { id: page.props.user!.id })"
                    >Edit Profile</Link
                  >
                </q-item-section>
              </q-item>
            </q-list>
          </q-menu>
        </q-item>
      </q-toolbar>
    </q-header>

    <q-drawer
      bordered
      v-model="drawerOpen"
      :mini="drawerMini"
      :show-if-above="true"
      @mouseover="expandDrawer"
      @mouseleave="collapseDrawer"
    >
      <q-scroll-area class="fit">
        <MenuItems :menu="page.props.menu"></MenuItems>
      </q-scroll-area>
    </q-drawer>

    <q-page-container>
      <q-page ref="pageRef" padding :style-fn="styleFn">
        <slot />
      </q-page>
    </q-page-container>

    <q-ajax-bar ref="bar" position="top" color="accent" size="10px" skip-hijack />
  </q-layout>
</template>

<script lang="ts" setup>
import { onMounted, ref } from "vue"
import { usePage, Link } from "@inertiajs/vue3"
import { QPage, useQuasar } from "quasar"
import MenuItems from "./Components/Menu/MenuItems.vue"
import { Head } from "@inertiajs/vue3"
import { useNotify } from "../composables/useNotify"
import { safeRoute } from "../helper"

interface Props {
  title?: string
}

defineProps<Props>()

const $q = useQuasar()
const page = usePage()
const pageRef = ref<InstanceType<typeof QPage> | null>(null)
const drawerOpen = ref(true)
const drawerMini = ref(false)
const isHoverMini = ref(false)
const profileMenuOpen = ref(false)
const pagePadding = ref(0)

const toggleDrawer = () => {
  if (drawerOpen.value) {
    if (drawerMini.value) {
      drawerOpen.value = false
      drawerMini.value = false
    } else {
      drawerMini.value = true
    }
  } else {
    drawerOpen.value = true
    drawerMini.value = false
  }
}

const expandDrawer = () => {
  if (drawerMini.value) {
    drawerMini.value = false
    isHoverMini.value = true
  }
}

const collapseDrawer = () => {
  if (drawerOpen.value && isHoverMini.value) {
    drawerMini.value = true
    isHoverMini.value = false
  }
}

const toggleDarkMode = () => {
  $q.dark.toggle()
  $q.localStorage.setItem("darkMode", $q.dark.mode)
}

const styleFn = (offset: number, height: number) => {
  return {
    minHeight: `${height - offset}px`,
    "--pageHeight": `${height - offset - pagePadding.value}px`
  }
}

useNotify()

onMounted(() => {
  const style = window.getComputedStyle(pageRef.value?.$el, null)
  pagePadding.value =
    parseInt(style.paddingTop.replace("px", "")) + parseInt(style.paddingBottom.replace("px", ""))
  $q.dark.set($q.localStorage.getItem("darkMode") ?? "auto")
})
</script>

<style lang="scss" scoped>
.user-avatar {
  a {
    text-decoration: none;
    font-weight: 500;
    color: #fff;
  }
}

.q-toolbar {
  min-height: 48px;

  .q-avatar {
    font-size: 32px;
  }
}

.logout {
  border: none;
  background: transparent;
  color: #fff;
  text-align: left;
  cursor: pointer;
}

:deep(.q-item.active) .q-focus-helper {
  background: currentColor;
  opacity: 0.15 !important;
}
</style>
