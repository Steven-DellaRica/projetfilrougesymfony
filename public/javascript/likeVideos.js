window.onload = () => {
    //On cherche à détecter tout les boutons de la page
    document.querySelectorAll(".likeButton").forEach(button => {

        //On attends un click de l'utilisateur sur le bouton
        button.addEventListener("click", () => {

            //On récupère les informations de la vidéo passées par l'input
            //Pour savoir la route à prendre lors de notre requête
            const videoId = button.getAttribute('data-video-id');
            const url = `/videopage/like-video/${videoId}`;

            //On fait une requête en asynchrone pour le travail du back-end
            fetch(url, {
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                },
            }).then(function (response) {

                //On attends la réponse de notre controleur et on exécute
                //notre logique pour définir si la vidéo est liké ou non
                if(button.getAttribute('class').includes('off')){
                    button.removeAttribute('class');
                    button.setAttribute('class', 'likeButton on');
                } else {
                    button.removeAttribute('class');
                    button.setAttribute('class', 'likeButton off');
                }

            }).catch(function (error) {
                //En cas d'erreur
                console.error("Erreur lors de l'ajout du like : " + error);
            });

        })
    })
}
