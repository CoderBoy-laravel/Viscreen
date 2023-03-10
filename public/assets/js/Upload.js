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


const bulkModalHandler = () => {
    if (show) {
        show = false;
        $('#bulk_modal').hide();
    } else {
        show = true;
        $('#bulk_modal').show();
    }

    $('#bulk_id').val('');
    $('#bulk_type').val('');
    $('#bulk_file').val('');
    $('#bulk_title').val('');
    $('#bulk_description').val('');
}

const  editBulkHandler = (data) =>{
    if (show) {
        show = false;
        $('#bulk_modal').hide();
    } else {
        show = true;
        $('#bulk_modal').show();
    }
    $('#bulk_id').val(data.id);
    $('#bulk_type').val(data.type);
    $('#bulk_file').val(data.file);
    $('#bulk_title').val(data.title);
    $('#bulk_description').val(data.description);
}

// Delete
const modalBulkDelHandler = (element) => {
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

// Player
const viewBulkHandler = (url, data) => {
    if (!view) {
        let div = document.createElement("div");
        div.id = "video_container" ;
        playerElement.appendChild(div);
        view = true;
        playerModal.style.display = 'block'
        seeTitle.innerText = data.title
        seeDescription.innerText = data.description
        let first_media = data.playlist[0]
        let media_url  = `${url}/assets/Upload/${data.file}${first_media}`

        $('#playList').html('<h2 style="font-weight: bold">Play List</h2>');
        for(i=0; i< data.playlist.length;i++){

            $('#playList').append('<a class="playListItem" data-url="'+`${url}/assets/Upload/${data.file}${data.playlist[i]}`+'">'+getName(data.playlist[i])+'</a>');
        }
        const config = {
            sources: [
                {
                    type: 'mp4',
                    src: media_url,

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

$('#frmBulkUpload').submit(function (e){
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: $(this).data('url'),
        data: $(this).serialize(),
        dataType:'json',
        success: function(response)
        {
             if(response == "success"){
                 show = false;
                 $('#bulk_modal').hide();
                 $('#bulk_message').html('');
                 location.reload();
             }else{
                 $('#bulk_message').html(respons.message);
             }
        }
    });
});

function getName(file){
    let arr = file.split('.');
    return arr[0];
}

$(document).on('click','.playListItem',function (){
    let media_url = $(this).data('url');
    let div = document.createElement("div");
    div.id = "video_container" ;

    $('#playList .active').removeClass('active');
    $(this).addClass('active');

    $(playerElement).html(div);
    const config = {
        sources: [
            {
                type: 'mp4',
                src: media_url,

            },
        ],
        ui: {
            image: '',
        },
    };
    player = IndigoPlayer.init(div, config);
});
