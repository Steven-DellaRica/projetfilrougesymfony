window.onload = () => {
    const cardDiv = document.querySelector("#video-card-bottom");

    //On boucle sur les buttons
    document.querySelectorAll(".likeButton").forEach(button => {
        button.addEventListener("click", () => {
            console.log('click');
            const videoId = button.getAttribute('data-video-id');
            const url = `/videopage/like-video/${videoId}`;

            fetch(url, {
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                },
            }).then(function (response) {
                console.log('Hello');
                console.log(response);
                if(button.getAttribute('class').includes('off')){
                    console.log(button.getAttribute('class').includes('off'));
                    button.removeAttribute('class');
                    button.setAttribute('class', 'likeButton on');
                } else {
                    console.log(button.getAttribute('class').includes('off'));
                    button.removeAttribute('class');
                    button.setAttribute('class', 'likeButton off');
                }

            }).catch(function (error) {
                console.error("Erreur lors de l'ajout du like : " + error);
            });

        })
    })
}