import.meta.glob(["../images/**"])
import axios, { AxiosError, type AxiosRequestConfig } from "axios"
import { themeChange } from "theme-change"

axios.defaults.timeout = 10000
axios.interceptors.response.use(
  (response) => {
    shiftPool(response)
    return response
  },
  (error: AxiosError<{ message?: string }>) => {
    const errMsgInRes = error.response?.data?.message ?? null
    if (errMsgInRes) {
      //@todo global notify
    }
    return Promise.reject(error)
  }
)

// 创建一个用于存储请求的池
const axiosPool = new Map<string, (...args: any[]) => unknown>()

// 生成唯一标识 URL 的方法
const generateUrl = (config: AxiosRequestConfig<unknown>): string => {
  const params = new URLSearchParams(config.params as Record<string, string>).toString()
  const data =
    typeof config.data === "object"
      ? JSON.stringify(config.data as Record<string, unknown>)
      : typeof config.data === "string"
        ? config.data
        : ""
  return [config.method ?? "GET", config.url ?? "", params, data].join("&")
}

// 添加请求到池中
const appendPool = (config: AxiosRequestConfig) => {
  const url = generateUrl(config)

  // 如果池中没有该请求，设置取消令牌
  config.cancelToken =
    config.cancelToken ??
    new axios.CancelToken((cancel) => {
      if (!axiosPool.has(url)) {
        axiosPool.set(url, cancel)
      }
    })
}

// 请求拦截器
axios.interceptors.request.use(
  (config) => {
    // 移除之前的相同请求
    shiftPool(config)

    // 添加当前请求到池中
    appendPool(config)

    return config
  },
  (error: AxiosError) => Promise.reject(error)
)

// 移除请求
const shiftPool = (config: AxiosRequestConfig) => {
  const url = generateUrl(config)

  if (axiosPool.has(url)) {
    // 调用取消函数
    const cancel = axiosPool.get(url)
    if (cancel) cancel(url)

    // 从池中移除
    axiosPool.delete(url)
  }
}

document.addEventListener("DOMContentLoaded", () => {
  themeChange(false)

  //copy mobile menu
  document.querySelector(".menu.mobile")!.innerHTML = document.querySelector(".menu.desktop")!.innerHTML
})
