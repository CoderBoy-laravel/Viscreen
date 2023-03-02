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
