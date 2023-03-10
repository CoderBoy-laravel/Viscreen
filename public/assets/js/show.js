let view = false;
const playerElement = document.getElementById('player');
const playerModal = document.getElementById('modalSee');
const viewHandler = (url, data) => {
    if (!view) {
        let div = document.createElement("div");
        div.id = "video_container" ;
        playerElement.appendChild(div);
        view = true;
        playerModal.style.display = 'block'
        seeTitle.innerText = data.title
        seeDescription.innerText = data.description
        $('#playList').html('');
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
