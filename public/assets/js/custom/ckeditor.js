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

/*ClassicEditor
    .create(document.querySelector('#editor'), {
        extraPlugins: [SimpleUploadAdapterPlugin],
        toolbar: [
            'heading', '|', 'bold', 'italic', '|', 'link', 'bulletedList', 'numberedList', '|', 'imageUpload', '|', 'undo', 'redo', '|', 'fullscreen'
        ]
    })
    .catch(error => {
        console.error(error);
    });*/
    ClassicEditor
    .create(document.querySelector('#editor'), {
        extraPlugins: [SimpleUploadAdapterPlugin], // Add your custom adapter
        toolbar: [
            'heading', '|', 'bold', 'italic', 'underline', 'strikethrough', '|',
            'fontSize', 'fontColor', 'fontBackgroundColor', '|',
            'link', 'bulletedList', 'numberedList', 'alignment', '|',
            'imageUpload', 'mediaEmbed', 'insertTable', '|',
            'undo', 'redo', '|', 'fullscreen'
        ],
        fontSize: {
            options: [
                'tiny',
                'small',
                'default',
                'big',
                'huge'
            ]
        },
        fontColor: {
            colors: [
                {
                    color: 'hsl(0, 0%, 0%)',
                    label: 'Black'
                },
                {
                    color: 'hsl(0, 0%, 30%)',
                    label: 'Dim gray'
                },
                {
                    color: 'hsl(0, 0%, 60%)',
                    label: 'Gray'
                },
                {
                    color: 'hsl(0, 0%, 90%)',
                    label: 'Light gray'
                },
                {
                    color: 'hsl(0, 0%, 100%)',
                    label: 'White',
                    hasBorder: true
                },
                {
                    color: 'hsl(0, 75%, 60%)',
                    label: 'Red'
                },
                {
                    color: 'hsl(30, 75%, 60%)',
                    label: 'Orange'
                },
                {
                    color: 'hsl(60, 75%, 60%)',
                    label: 'Yellow'
                },
                {
                    color: 'hsl(90, 75%, 60%)',
                    label: 'Light green'
                },
                {
                    color: 'hsl(120, 75%, 60%)',
                    label: 'Green'
                },
                {
                    color: 'hsl(150, 75%, 60%)',
                    label: 'Aquamarine'
                },
                {
                    color: 'hsl(180, 75%, 60%)',
                    label: 'Turquoise'
                },
                {
                    color: 'hsl(210, 75%, 60%)',
                    label: 'Light blue'
                },
                {
                    color: 'hsl(240, 75%, 60%)',
                    label: 'Blue'
                },
                {
                    color: 'hsl(270, 75%, 60%)',
                    label: 'Purple'
                }
            ]
        },
        table: {
            contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
        },
        mediaEmbed: {
            previewsInData: true // Allows embedding previews in the editor
        }
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
