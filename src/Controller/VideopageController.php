<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\TagsRepository;
use App\Repository\UserRepository;
use App\Service\UtilsService;
use App\Repository\VideosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/videopage')]
class VideopageController extends AbstractController
{
    #[Route('/', name: 'app_videopage', methods: ['GET', 'POST'])]
    public function index(Request $request, VideosRepository $videosRepository, UserRepository $userRepo, TagsRepository $tagsRepository): Response
    {
        $form = $this->createForm(SearchType::class);

        // Par défaut on affiche toutes les vidéos
        $videosDisplay = $videosRepository->findAll();
        $form->handleRequest($request);
        $userVideoLikeId = null;

        $user = $this->getUser();

        if($user){
            $userId = $user->getId();
            $userVideoLike = $userRepo->getAllVideoLike($userId);

            foreach($userVideoLike as $userVideoId){
                $userVideoLikeId[] = $userVideoId["videos_id"];
            }

        }

        // dd($userVideoLikeId);
        

        // La demande de requête a bien été envoyée pour effectuer la recherche
        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->getData()['searchBarTags'] === null) {
                // && $form->getData()['searchBarAuthors'] === null
                // Si l'utilisateur appuye sur le bouton Rechercher sans rien écrire, on remet toutes les vidéos au cas ou
                $videosDisplay = $videosRepository->findAll();
            } else {
                // Nettoie les variables des utilisateurs et les divise par mots
                if ($form->getData()['searchBarTags'] !== null) {
                    $searchTags = explode(" ", UtilsService::cleanInput($form->getData()['searchBarTags']));
                } else {
                    $searchTags = [];
                }

                // if ($form->getData()['searchBarAuthors'] !== null) {
                //     $searchAuthors = explode(" ", UtilsService::cleanInput($form->getData()['searchBarAuthors']));
                // } else {
                //     $searchAuthors = [];
                // }

                //On regroupe les résultats dans un tableau
                $searchInput = array_merge($searchTags);
                
                //On récupère les tags recherchés par l'utilisateur
                foreach ($searchTags as $tag) {
                    if(!empty($tagsRepository->findBy(['tags_libelle' => $tag]))){
                        $tagArray[] = $tagsRepository->findBy(['tags_libelle' => $tag]);
                    }
                }
                // $tagArray = array_merge(...$tagArray);
                $vide = true;
                
                //On vérifie si au moins un tag est valide et présent dans la BDD
                foreach ($tagArray as $index => $tag) {
                    if (!empty($tag)) {
                        $vide = false;
                    } else {
                        unset($tagArray[$index]);
                        $tagArray = array_values($tagArray);
                    }
                }
                
                if ($vide === true) {
                    // Envoi un tableau vide si aucun tag ou auteur ne correspond à la recherche
                    $videosDisplay = null;
                } else {
                    // Récupérer l'id des tags attribués aux vidéos
                    foreach ($tagArray as $tag) {
                        $tagId[] = $tag[0]->getId();
                    }
                    // Récupérer l'id des vidéos concernées
                    foreach ($tagId as $tag) {
                        $tempVideosDisplay[] = $videosRepository->getVideoTags($tag);
                    }
                    $videosDisplay = array_merge(...$tempVideosDisplay);
                    
                    // Récupérer l'objet vidéo correspondant à l'id récupérée
                    foreach ($videosDisplay as $videoId) {
                        if (empty($videosRepository->find($videoId['video_id']))) {
                            echo '<pre>La vidéo n existe pas ' . var_export($videoId['video_id'], true) . '</pre>';
                        } else {
                            $tempVideosDisplay2[] = $videosRepository->find($videoId['video_id']);
                        }
                    }
                    $videosDisplay = $tempVideosDisplay2;
                }
            }
        }

        return $this->render('videopage/index.html.twig', [
            'user' => $user,
            'userVideoLike' => $userVideoLikeId,
            'videos' => $videosDisplay,
            'form' => $form,
        ]);
    }

    #[Route('/like-video/{videoYoutubeId}', name: 'like_video', methods: ['GET', 'POST'])]
    public function likeVideo(Request $request, VideosRepository $videosRepository, UserRepository $userRepo, EntityManagerInterface $em, string $videoYoutubeId): Response
    {
        $video = $videosRepository->findOneBy(['video_id' => $videoYoutubeId]);
        $videoRealId = $video->getId();
        $user = $this->getUser();
        $userId = $this->getUser()->getId();

        $userInstance = $userRepo->find($userId);

        $userVideoLike = $userRepo->getVideoLike($videoRealId, $userId);


        if($user === null){
            throw new NotFoundHttpException('User not logged');
        }

        if($video === null){
            throw new NotFoundHttpException('Video not found');
        }

        if($userVideoLike){
            $userInstance->removeVideoLike($video);
            $video->setVideoLikes($video->getVideoLikes() - 1);

            $em->persist($userInstance);
            $em->flush();

            return new JsonResponse(['result' => 'success']);
        } else {
            $userInstance->addVideoLike($video);
            $video->setVideoLikes($video->getVideoLikes() + 1);

            $em->persist($userInstance);
            $em->flush();
            
            return new JsonResponse(['result' => 'success']);
        }
        
    }
}
