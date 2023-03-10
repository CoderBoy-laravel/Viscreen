let show = false;
let view = false;
let edit = false;
let show1 = false;
const modal = document.getElementById('modal');
const playerModal = document.getElementById('modalSee');
const modalEdit = document.getElementById('modalEdit');
const from = document.getElementById('fileUpload');
const editUpload = document.getElementById('editUpload');
const typeChooseText = document.getElementById('typeChoose');
const FileChoose = document.getElementById('FileChoose');
const fileError = document.getElementById('fileError');
const titleError = document.getElementById('titleError');
const descriptionError = document.getElementById('descriptionError');
const seeTitle = document.getElementById('seeTitle');
const seeDescription = document.getElementById('seeDescription');
const playerElement = document.getElementById('player');
const modal1 = document.getElementById('modalDelete');

// Edit Elements
const editId = document.getElementById('editId');
const editSelect = document.getElementById('editSelect');
const edittitle = document.getElementById('edittitle');
const editdescription = document.getElementById('editdescription');
const edittypeChoose = document.getElementById('edittypeChoose');
const editFileChoose = document.getElementById('editFileChoose');
const editfileError = document.getElementById('editfileError');
const edittitleError = document.getElementById('edittitleError');
const editdescriptionError = document.getElementById('editdescriptionError');
// MOdal
const modalHandler = () => {
    if (show) {
        show = false;
        modal.style.display = 'none'
    } else {
        show = true;
        modal.style.display = 'flex'
    }
}

// UPload
from.addEventListener('submit', function (ev) {
    ev.preventDefault();
    $.ajax({
        xhr: function () {
            var progress = $('.progress'),
                xhr = $.ajaxSettings.xhr();

            progress.show();

            xhr.upload.onprogress = function (ev) {
                if (ev.lengthComputable) {
                    var percentComplete = parseInt((ev.loaded / ev.total) * 100);
                    progress.val(percentComplete);
                    if (percentComplete === 100) {
                        progress.hide().val(0);
                    }
                }
            };

            return xhr;
        },
        url: from.dataset.url,
        type: 'POST',
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        success: function (data, status, xhr) {
            location.reload();
        },
        error: function (xhr, status, error) {
            const errors = xhr.responseJSON.errors;
            if (errors.title) {
                titleError.innerText = errors.title[0]
            } else {
                titleError.innerText = ''
            }
            if (errors.description) {
                descriptionError.innerText = errors.description[0]
            } else {
                descriptionError.innerText = ''
            }
            if (errors.file) {
                fileError.innerText = errors.file[0]
            } else {
                fileError.innerText = ''
            }
        }
    });
});

// Type of
const typeChoose = (elm, type) => {
    if (type === 'file') {
        typeChooseText.innerText = 'File'
        FileChoose.setAttribute('name', 'file')
        FileChoose.setAttribute('type', 'file')
    } else {
        typeChooseText.innerText = 'Link'
        FileChoose.setAttribute('name', 'link')
        FileChoose.setAttribute('type', 'text')
    }
}

// Player
const viewHandler = (url, data) => {
    if (!view) {
        let div = document.createElement("div");
        div.id = "video_container" ;
        playerElement.appendChild(div);
        view = true;
        playerModal.style.display = 'block'
        seeTitle.innerText = data.title
        seeDescription.innerText = data.description
        const config = {
            sources: [
              {
                type: 'mp4',
                src: data.file ? `${url}${data.file}` : data.link,

              },
            ],
            ui: {
                image: data.file && `${url}${data.thumb}`,
              },
          };
        player = IndigoPlayer.init(div, config);
    }
    else{
        const element = document.getElementById('video_container');
        view = false;
        playerModal.style.display = 'none'
        seeTitle.innerText = ''
        seeDescription.innerText = ''
        element.remove();
    }
}

// Edit
const editHandler = (data) => {
    if (edit) {
        edit = false;
        modalEdit.style.display = 'none'
    } else {
        edit = true;
        modalEdit.style.display = 'flex'
        editId.value = data.id
        editSelect.value = data.type
        edittitle.value = data.title
        editdescription.value = data.description
        console.log();
        if (data.type.length > 5) {
            edittypeChoose.innerText = 'Link'
            editFileChoose.setAttribute('name', 'link')
            editFileChoose.setAttribute('type', 'text')
        }
    }
}

// Type of Edit
const editTypeChoose = (elm, type) => {
    if (type === 'file') {
        edittypeChoose.innerText = 'File'
        editFileChoose.setAttribute('name', 'file')
        editFileChoose.setAttribute('type', 'file')
    } else {
        edittypeChoose.innerText = 'Link'
        editFileChoose.setAttribute('name', 'link')
        editFileChoose.setAttribute('type', 'text')
    }
}

// Edit Upload
editUpload.addEventListener('submit', function (ev) {
    ev.preventDefault();
    $.ajax({
        xhr: function () {
            var progress = $('.progress'),
                xhr = $.ajaxSettings.xhr();

            progress.show();

            xhr.upload.onprogress = function (ev) {
                if (ev.lengthComputable) {
                    var percentComplete = parseInt((ev.loaded / ev.total) * 100);
                    progress.val(percentComplete);
                    if (percentComplete === 100) {
                        progress.hide().val(0);
                    }
                }
            };

            return xhr;
        },
        url: editUpload.dataset.url,
        type: 'POST',
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        success: function (data, status, xhr) {
            location.reload();
        },
        error: function (xhr, status, error) {
            const errors = xhr.responseJSON.errors;
            if (errors.title) {
                edittitleError.innerText = errors.title[0]
            } else {
                edittitleError.innerText = ''
            }
            if (errors.id) {
                toastr.error(errors.id[0])
            }
            if (errors.description) {
                editdescriptionError.innerText = errors.description[0]
            } else {
                editdescriptionError.innerText = ''
            }
            if (errors.file) {
                editfileError.innerText = errors.file[0]
            } else {
                editfileError.innerText = ''
            }
        }
    });
});

// Delete
const modalDelHandler = (element) => {
    if (show1) {
        show1 = false;
        modal1.style.display = 'none'
    }else{
        const deletUserLink = document.getElementById('deletUploadLInk');
        deletUserLink.setAttribute('href', element.getAttribute('data-url'))
        show1 = true;
        modal1.style.display = 'flex'
    }
}

// Filet
const filterHandler = (element, url) => {
    location.href = `${url}/upload/${element.value}`
}
