import { watch, nextTick } from "vue"
import { usePage } from "@inertiajs/vue3"
import { useQuasar } from "quasar"
import type { InertiaNotify } from "../types/inertia"

let lastWasPopState = false
window.addEventListener("popstate", () => {
  lastWasPopState = true
})

export function useNotify() {
  const $q = useQuasar()
  const page = usePage<{ inertiaNotify?: InertiaNotify[] }>()

  watch(
    () => page.props.inertiaNotify,
    async (notifies) => {
      await nextTick()

      if (lastWasPopState) {
        lastWasPopState = false
        return
      }

      notifies?.forEach((notify: InertiaNotify) => {
        if (notify) {
          $q.notify({
            message: notify.message,
            type: notify.type,
            position: notify.position,
            caption: notify.caption
          })
        }
      })
    },
    { immediate: true }
  )
}
