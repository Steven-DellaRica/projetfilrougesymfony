window.onload = () => {
    const cardDiv = document.querySelector("#video-card-bottom");

    //On boucle sur les buttons
    document.querySelectorAll(".likeButton").forEach(button => {
        button.addEventListener("click", () => {
            console.log('click');
            console.log(button.getAttribute('data-video-id'));
            const videoId = button.getAttribute('data-video-id');
            const url = `/videopage/like-video/${videoId}`;

            fetch(url, {
                // method: 'POST',
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                },
                // body: JSON.stringify({videoId: videoId})
            }).then(function (response) {
                // if (!response.ok) {
                //     throw new Error('Erreur lors de la requête.' + response);
                // }
                // return response.json();
                console.log('Hello');
                console.log(response);
            })
                // .then(function(data){
                //     console.log('success !', data);
                // })
                .catch(function (error) {
                    console.error("Erreur lors de l'ajout du like : " + error);
                });

            // button.getAttribute('data-video-id');
        })
    })
}