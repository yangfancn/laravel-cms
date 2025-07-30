import { Cookies } from "quasar";
class MyUploadAdapter {
    loader;
    xhr;
    constructor(loader) {
        this.loader = loader;
        this.xhr = null;
    }
    async upload() {
        return this.loader.file.then((file) => new Promise((resolve, reject) => {
            this._initRequest();
            this._initListeners(resolve, reject, file);
            this._sendRequest(file);
        }));
    }
    abort() {
        if (this.xhr) {
            this.xhr.abort();
        }
    }
    _initRequest() {
        this.xhr = new XMLHttpRequest();
        this.xhr.open("POST", "/manager/upload", true);
        this.xhr.responseType = "json";
        this.xhr.setRequestHeader("X-XSRF-TOKEN", Cookies.get("XSRF-TOKEN"));
    }
    // Initializes XMLHttpRequest listeners.
    _initListeners(resolve, reject, file) {
        const loader = this.loader;
        const xhr = this.xhr;
        const genericErrorText = `Couldn't upload file: ${file.name}.`;
        xhr.addEventListener("error", () => {
            reject(genericErrorText);
        });
        xhr.addEventListener("abort", () => reject());
        xhr.addEventListener("load", () => {
            const response = xhr.response;
            if (!response || response.error) {
                return reject(response && response.error ? response.error.message : genericErrorText);
            }
            resolve({
                default: response.url
            });
        });
        if (xhr.upload) {
            xhr.upload.addEventListener("progress", (evt) => {
                if (evt.lengthComputable) {
                    loader.uploadTotal = evt.total;
                    loader.uploaded = evt.loaded;
                }
            });
        }
    }
    _sendRequest(file) {
        const data = new FormData();
        data.append("file", file);
        this.xhr.send(data);
    }
}
export function MyCustomUploadAdapterPlugin(editor) {
    editor.plugins.get("FileRepository").createUploadAdapter = (loader) => {
        return new MyUploadAdapter(loader);
    };
}
