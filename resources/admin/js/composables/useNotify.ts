import { watch } from "vue"
import { usePage } from "@inertiajs/vue3"
import { useQuasar } from "quasar"
import { InertiaNotify } from "../types/inertia"

export function useNotify() {
  const $q = useQuasar()
  const page = usePage()

  watch(
    () => page.props.inertiaNotify,
    (notifies) => {
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
      // 清空消息，防止页面回退时再次弹出消息
      page.props.inertiaNotify?.splice(0, page.props.inertiaNotify.length)
    },
    { immediate: true }
  )
}
