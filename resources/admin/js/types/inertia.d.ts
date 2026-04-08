import type { Page, PageProps } from "@inertiajs/core"

interface InertiaNotify {
  type: "positive" | "negative" | "warning" | "info"
  message: string
  caption?: string
  position: "top-left" | "top" | "top-right" | "left" | "center" | "right" | "bottom-left" | "bottom" | "bottom-right"
}

interface SharedProps extends PageProps {
  inertiaNotify?: InertiaNotify[]
  user?: {
    id: number
    name: string
    photo?: string
    roles: {
      name: string
    }[]
  }
  menu: MenuItem[]
}

declare module "@inertiajs/vue3" {
  export function usePage(): Page<SharedProps>
}
