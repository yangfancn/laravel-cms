import { Editor } from "ckeditor5"
import type { FileLoader, UploadAdapter } from "ckeditor5"
import { Cookies } from "quasar"

type CkUploadResponse = Record<string, unknown>

class MyUploadAdapter implements UploadAdapter {
  private readonly loader: FileLoader
  private xhr: XMLHttpRequest | null

  constructor(loader: FileLoader) {
    this.loader = loader
    this.xhr = null
  }

  async upload(): Promise<CkUploadResponse> {
    return this.loader.file.then(
      (file: File | null) =>
        new Promise<CkUploadResponse>((resolve, reject) => {
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
    resolve: (value: CkUploadResponse | PromiseLike<CkUploadResponse>) => void,
    reject: (reason?: unknown) => void,
    file: File
  ): void {
    const loader = this.loader
    const xhr = this.xhr!

    const genericErrorText = `Couldn't upload file: ${file.name}.`

    xhr.addEventListener("error", () => {
      reject(genericErrorText)
    })
    xhr.addEventListener("abort", () => reject())
    xhr.addEventListener("load", () => {
      const response = xhr.response as UploadResponse

      if (!response?.url) {
        return reject(response?.message ?? "Upload failed")
      }

      resolve({ default: response.url })
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
