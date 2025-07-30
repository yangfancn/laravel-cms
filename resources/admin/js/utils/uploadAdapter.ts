import { Editor } from "ckeditor5"
import type { FileLoader, UploadAdapter, UploadResponse } from "ckeditor5"
import { Cookies } from "quasar"

class MyUploadAdapter implements UploadAdapter {
  private readonly loader: FileLoader
  private xhr: XMLHttpRequest | null

  constructor(loader: FileLoader) {
    this.loader = loader
    this.xhr = null
  }

  async upload(): Promise<UploadResponse> {
    return this.loader.file.then(
      (file: File | null) =>
        new Promise((resolve, reject) => {
          this._initRequest()
          this._initListeners(resolve, reject, file!)
          this._sendRequest(file!)
        })
    )
  }

  abort() {
    if (this.xhr) {
      this.xhr.abort()
    }
  }

  _initRequest() {
    this.xhr = new XMLHttpRequest()
    this.xhr.open("POST", "/manager/upload", true)
    this.xhr.responseType = "json"
    this.xhr.setRequestHeader("X-XSRF-TOKEN", Cookies.get("XSRF-TOKEN"))
  }

  // Initializes XMLHttpRequest listeners.
  _initListeners(
    resolve: (
      value:
        | { default: string }
        | PromiseLike<{
          default: string
        }>
    ) => void,
    reject: (reason?: any) => void,
    file: File
  ): void {
    const loader = this.loader
    const xhr = this.xhr!

    const genericErrorText = `Couldn't upload file: ${file.name}.`

    xhr!.addEventListener("error", () => {
      reject(genericErrorText)
    })
    xhr!.addEventListener("abort", () => reject())
    xhr!.addEventListener("load", () => {
      const response = xhr.response

      if (!response || response.error) {
        return reject(response && response.error ? response.error.message : genericErrorText)
      }

      resolve({
        default: response.url
      })
    })

    if (xhr.upload) {
      xhr.upload.addEventListener("progress", (evt) => {
        if (evt.lengthComputable) {
          loader.uploadTotal = evt.total
          loader.uploaded = evt.loaded
        }
      })
    }
  }

  _sendRequest(file: File) {
    const data = new FormData()
    data.append("file", file)
    this.xhr!.send(data)
  }
}

export function MyCustomUploadAdapterPlugin(editor: Editor) {
  editor.plugins.get("FileRepository").createUploadAdapter = (loader: FileLoader) => {
    return new MyUploadAdapter(loader)
  }
}
