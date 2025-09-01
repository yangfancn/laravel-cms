type route = typeof import("ziggy-js").route

interface Window {
  route: route
}

interface FilePondFile {
  file: File
  id: string
  serverId: string
  status: number
  fileSize: number
  fileType: string
  fileName: string
  metadata: object
}

interface MenuItem {
  id: number
  name: string
  icon?: string
  icon_color: string | null
  route: string | null
  params:
  | Record<string, any>[]
  | null
  show_in_menu: boolean
  children?: MenuItem[]
  label: string
}

interface DashboardBot {
  id: number
  baidu: number
  bing: number
  duckduckgo: number
  google: number
  yandex: number
  other: number
  created_at: string
}

interface DashboardPageVisits {
  visit_date: string
  page_views: number
}

interface DashboardUniqueVisitors {
  visit_date: string
  unique_ip_views: number
}

interface DashboardVisitDistribution {
  country: string
  count: number
}

type InertiaRequestMethod = "get" | "post" | "put" | "patch" | "delete"

interface UploadResponse {
  message: string
  url?: string
}

