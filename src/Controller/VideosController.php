<?php

namespace App\Controller;

use DateTime;
use App\Entity\Videos;
use App\Entity\User;
use App\Form\VideoIDType;
use App\Form\VideoTagsType;
use App\Service\UtilsService;
use App\Repository\VideosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/videos')] // Je tente le changement d'enlever le adminpage "/adminpage"
class VideosController extends AbstractController
{
    public function getYoutubeInfos(string $videoId)
    {
        $apiKey = $this->getParameter('app.googleapi');

        $apiEndpoint = 'https://www.googleapis.com/youtube/v3/videos';

        $params = [
            'id' => $videoId,
            'key' => $apiKey,
            'part' => 'snippet, contentDetails, statistics',
        ];

        $query = http_build_query($params);
        $response = file_get_contents($apiEndpoint . '?' . $query);
        $responseData = json_decode($response, true);

        //Check si l'id reçu est valide
        if ($responseData['items']) {
            $videoSnippet = $responseData['items'][0];
        } else {
            $videoSnippet = false;
        }

        return $videoSnippet;
    }

    public function setYoutubeInfos(Videos $video, array $youtubeInfos, User $currentUser)
    {
        $video->setVideoTitle($youtubeInfos['snippet']['title']);
        $video->setVideoAuthor($youtubeInfos['snippet']['channelTitle']);
        $video->setVideoLikes(0);
        $dateString = $youtubeInfos['snippet']['publishedAt'];
        $videoDateConverted = new DateTime($dateString);
        $video->setVideoDate($videoDateConverted);
        $video->setVideoThumbnail($youtubeInfos['snippet']['thumbnails']['high']['url']);
        $video->setVideoUserPoster($currentUser);

        return $video;
    }

    public function getYoutubeIdFromUrl(string $url)
    {
        $url = filter_var($url, FILTER_SANITIZE_URL);

        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $urlParts = parse_url($url);
            if (str_contains($urlParts['host'], 'youtube') || str_contains($urlParts['host'], 'youtu.be')) {
                if (isset($urlParts['query'])) {
                    parse_str($urlParts['query'], $urlQuery);

                    if (isset($urlQuery['v'])) {
                        return $urlQuery['v'];
                    } else if (isset($urlQuery['vi'])) {
                        return $urlQuery['vi'];
                    }
                }
                if (isset($urlParts['path'])) {
                    $path = explode('/', trim($urlParts['path'], '/'));
                    return $path[count($path) - 1];
                }
            }
        }

        return false;
    }

    #[Route('/adminpage', name: 'app_videos_index', methods: ['GET'])]
    public function index(VideosRepository $videosRepository): Response
    {
        return $this->render('videos/index.html.twig', [
            'videos' => $videosRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_videos_new', methods: ['GET', 'POST'])]
    public function new(Request $request, VideosRepository $videosRepository): Response
    {
        //Set up des variables dont on a besoin
        $partOne = true;
        $errorBool = false;
        $errorMsg = '';

        //On crée une nouvelle instance pour la vidéo que l'on va traiter et stocker
        $video = new Videos();

        //On va chercher les vidéos déjà stockée dans notre BDD
        $videosStocked = $videosRepository->findAll();

        //On crée notre Form pour récupérer nos informations
        $form = $this->createForm(VideoIDType::class, $video);

        $form->handleRequest($request);

        //Check si tout va bien
        if ($form->isSubmitted() && $form->isValid()) {

            //On nettoie l'input qu'on a reçu pour enlever les script ou tentatives d'injection SQL
            $url = UtilsService::cleanInput($request->request->all('video_id')['video_id']);

            //On lance notre fonction qui va isoler l'id de la video
            $videoId = $this->getYoutubeIdFromUrl($url);

            //Check si le lien est valide et plateforme reconnue
            if ($videoId !== false && $videoId !== '') {
                //On check si la vidéo existe déjà dans la BDD
                $duplicateId = false;
                foreach ($videosStocked as $video => $value) {

                    if ($value->getVideoId() === $videoId) {
                        $duplicateId = true;
                    }
                }

                if ($duplicateId === true) {
                    //La vidéo existe déjà
                    $errorBool = true;
                    $errorMsg = 'La vidéo existe déjà';
                } else {
                    //La vidéo n'existe pas dans la BDD, on passe à la suite
                    return $this->redirectToRoute('app_videos_new_tags', ['videoId' => $videoId], Response::HTTP_SEE_OTHER);
                }
            } else {
                //Scénario si le lien n'est pas valide
                $errorBool = true;
                $errorMsg = 'Le lien n\'est pas valide, ou la plateforme n\'est pas reconnue';
            }
        }

        //Variable que l'on fait passer à notre page twig
        return $this->render('videos/new.html.twig', [
            'partOne' => $partOne,
            'errorBool' => $errorBool,
            'errorMsg' => $errorMsg,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new/{videoId}', name: 'app_videos_new_tags', methods: ['GET', 'POST'])]
    public function newId(Request $request, EntityManagerInterface $entityManager, string $videoId): Response
    {
        //Set up des variables dont on a besoin
        $partOne = false;
        $errorBool = false;
        $errorMsg = '';

        //On crée une nouvelle instance pour la vidéo que l'on va traiter et stocker
        $video = new Videos();
        //On récupère l'utilisateur qui fait l'opération
        $user = $this->getUser();
        //On traite l'input qu'on a reçu pour enlever les script ou tentatives d'injection SQL
        $videoId = UtilsService::cleanInput($videoId);

        //On lance notre fonction qui va récupérer les infos de la vidéo via la google API
        $videoInfos = $this->getYoutubeInfos($videoId);

        //On vérifie si quelqu'un n'a pas mis n'importe quoi dans l'url
        if (!$videoInfos) {
            $errorBool = true;
            $errorMsg = 'Cette vidéo n\'existe pas';
        } else {
            //On set la video id de notre instance
            $video->setVideoId($videoId);

            //On lance notre fonction qui va attribuer les infos récupérées à notre instance vidéo
            $this->setYoutubeInfos($video, $videoInfos, $user);

            //On crée notre formulaire qui va récupérer nos tags
            $form = $this->createForm(VideoTagsType::class, $video);

            $form->handleRequest($request);
            //Check si tout se passe bien
            if ($form->isSubmitted() && $form->isValid()) {
                //On récupère les tags entré par l'utilisateur
                $tags = $form->get('tags')->getData();

                //On attribut les tags à l'instance de la vidéo
                for ($i = 0; $i < count($tags); $i++) {
                    $video->addTag($tags[$i]);
                }

                //On envoi notre vidéo à notre BDD pour la stocker
                $entityManager->persist($video);
                $entityManager->flush();

                //On redirige l'utilisateur différemment selon si c'est l'admin ou non
                if ($user->getRoles() == "ROLE_ADMIN") {
                    return $this->redirectToRoute('app_videos_index', [], Response::HTTP_SEE_OTHER);
                } else {
                    return $this->redirectToRoute('app_videopage', [], Response::HTTP_SEE_OTHER);
                }
            }
        }


        return $this->render('videos/new.html.twig', [
            'partOne' => $partOne,
            'errorBool' => $errorBool,
            'errorMsg' => $errorMsg,
            'video' => $video,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_videos_show', methods: ['GET'])]
    public function show(Videos $video): Response
    {
        return $this->render('videos/show.html.twig', [
            'video' => $video,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_videos_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Videos $video, EntityManagerInterface $entityManager): Response
    {
        $partOne = false;
        $errorBool = false;
        $errorMsg = '';

        $form = $this->createForm(VideoTagsType::class, $video);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $tags = $form->get('tags')->getData();

            for ($i = 0; $i < count($tags); $i++) {
                $video->addTag($tags[$i]);
            }

            $entityManager->persist($video);
            $entityManager->flush();

            return $this->redirectToRoute('app_videos_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('videos/edit.html.twig', [
            'partOne' => $partOne,
            'errorBool' => $errorBool,
            'errorMsg' => $errorMsg,
            'video' => $video,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_videos_delete', methods: ['POST'])]
    public function delete(Request $request, Videos $video, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $video->getId(), $request->request->get('_token'))) {
            $entityManager->remove($video);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_videos_index', [], Response::HTTP_SEE_OTHER);
    }
}
