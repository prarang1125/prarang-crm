class MyUploadAdapter {
    constructor(loader) {
        this.loader = loader;
    }

    upload() {
        return this.loader.file.then(file => new Promise((resolve, reject) => {
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
        const xhr = this.xhr = new XMLHttpRequest();
        xhr.open('POST', uploadUrl, true);
        xhr.setRequestHeader('x-csrf-token', csrfToken);
        if (typeof postId !== 'undefined' && postId !== null && postId !== '') {
            xhr.setRequestHeader('ids', postId);
        }
        xhr.responseType = 'json';
    }

    _initListeners(resolve, reject, file) {
        const xhr = this.xhr;
        const loader = this.loader;
        const genericErrorText = `Couldn't upload file: ${file.name}.`;

        xhr.addEventListener('error', () => reject(genericErrorText));
        xhr.addEventListener('abort', () => reject());
        xhr.addEventListener('load', () => {
            const response = xhr.response;
            if (!response || response.error) {
                return reject(response && response.error ? response.error.message : genericErrorText);
            }

            resolve({
                default: response.url
            });
        });

        if (xhr.upload) {
            xhr.upload.addEventListener('progress', evt => {
                if (evt.lengthComputable) {
                    loader.uploadTotal = evt.total;
                    loader.uploaded = evt.loaded;
                }
            });
        }
    }

    _sendRequest(file) {
        const data = new FormData();
        data.append('upload', file);
        this.xhr.send(data);
    }
}

function SimpleUploadAdapterPlugin(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
        return new MyUploadAdapter(loader);
    };
}

ClassicEditor
    .create(document.querySelector('#editor'), {
        extraPlugins: [SimpleUploadAdapterPlugin],
        toolbar: [
            'heading', '|', 'bold', 'italic', '|', 'link', 'bulletedList', 'numberedList', '|', 'imageUpload', '|', 'undo', 'redo', '|', 'fullscreen'
        ]
    })
    .catch(error => {
        console.error(error);
    });

function calculateTotal() {
    const fields = ['citySubscribers', 'prarangApplication', 'websiteGd', 'email', 'instagram'];
    let total = 0;

    fields.forEach(field => {
        const value = parseFloat(document.getElementById(field).value) || 0;
        total += value;
    });

    document.getElementById('total').value = total;
}
