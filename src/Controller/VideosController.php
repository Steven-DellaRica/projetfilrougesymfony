<?php

namespace App\Controller;

use App\Entity\Videos;
use App\Form\VideosType;
use App\Form\VideoIDType;
use App\Form\VideoTagsType;
use App\Repository\VideosRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/videos')]
class VideosController extends AbstractController
{
    public function getYoutubeInfos(string $videoId)
    {
        $apiKey = 'AIzaSyBCiw3CdxnZKQNx6MTOViqP4PXWNMDPCSA';

        $apiEndpoint = 'https://www.googleapis.com/youtube/v3/videos';

        $params = [
            'id' => $videoId,
            'key' => $apiKey,
            'part' => 'snippet, contentDetails, statistics',
        ];

        $query = http_build_query($params);
        $response = file_get_contents($apiEndpoint . '?' . $query);
        $responseData = json_decode($response, true);

        $videoSnippet = $responseData['items'][0];

        return $videoSnippet;
    }

    public function setYoutubeInfos(Videos $video, array $youtubeInfos)
    {
        $video->setVideoTitle($youtubeInfos['snippet']['title']);
        $video->setVideoAuthor($youtubeInfos['snippet']['channelTitle']);
        $video->setVideoLikes(0);
        $dateString = $youtubeInfos['snippet']['publishedAt'];
        $videoDateConverted = new DateTime($dateString);
        $video->setVideoDate($videoDateConverted);
        $video->setVideoThumbnail($youtubeInfos['snippet']['thumbnails']['high']['url']);

        return $video;
    }

    public function getYoutubeIdFromUrl(string $url)
    {
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
        return false;
    }

    #[Route('/', name: 'app_videos_index', methods: ['GET'])]
    public function index(VideosRepository $videosRepository): Response
    {
        return $this->render('videos/index.html.twig', [
            'videos' => $videosRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_videos_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $displayInfos = false;
        $errorBool = false;
        $errorMsg = '';
        $videoInfos = null;
        $video = new Videos();

        $form = $this->createForm(VideoIDType::class, $video);
        $form2 = $this->createForm(VideoTagsType::class, $video);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $url = $form->get('video_id')->getData();
            $videoID = $this->getYoutubeIdFromUrl($url);
            
            if (isset($videoID) != false) {
                $displayInfos = true;
                $videoInfos = $this->getYoutubeInfos($videoID);
                $this->setYoutubeInfos($video, $videoInfos);
                
                $entityManager->persist($video);
                $entityManager->flush();
            } else {
                //Handle if return false
                $errorBool = true;
                $errorMsg = 'Le lien de la vidéo n\'est pas valide';
                return $this->redirectToRoute('app_videos_new', [], Response::HTTP_SEE_OTHER);
            }
        } else {
            // Handle fail form submit
        }
        
        $form2->handleRequest($request);

        if ($form2->isSubmitted() && $form2->isValid()) {
            $datas = $form2->get('tags')->getData();

            for ($i = 0; $i < count($datas); $i++) {
                $video->addTag($datas[$i]);
            }


            $entityManager->flush();

            return $this->redirectToRoute('app_videos_index', [], Response::HTTP_SEE_OTHER);
        }

        // if ($form->isSubmitted() && $form->isValid()) {

        //     $url = $form->get('video_id')->getData();

        //     $videoID = $this->getYoutubeIdFromUrl($url);


        //     if (isset($videoID) != false) {
        //         $displayInfos = true;
        //         $videoInfos = $this->getYoutubeInfos($videoID);
        //         $this->setYoutubeInfos($video, $videoInfos);

        //         $form2->handleRequest($request);

        //         //https://symfony.com/doc/current/form/multiple_buttons.html

        //         if ($form2->isSubmitted() && $form2->isValid()) {

        //             $datas = $form2->get('tags')->getData();

        //             for ($i = 0; $i < count($datas); $i++) {
        //                 $video->addTag($datas[$i]);
        //             }

        //             dd($video);

        //             $entityManager->persist($video);
        //             $entityManager->flush();

        //             return $this->redirectToRoute('app_videos_index', [], Response::HTTP_SEE_OTHER);
        //         } else {
        //             dd('Va niquer tes grand morts');
        //             $errorBool = true;
        //             $errorMsg = 'Le 2eme formulaire n\'est pas valide';
        //         }
        //     } else {
        //         //Handle if return false
        //         $errorBool = true;
        //         $errorMsg = 'Le lien de la vidéo n\'est pas valide';
        //         return $this->redirectToRoute('app_videos_new', [], Response::HTTP_SEE_OTHER);
        //     }
        // }

        return $this->render('videos/new.html.twig', [
            'displayInfos' => $displayInfos,
            'errorBool' => $errorBool,
            'errorMsg' => $errorMsg,
            'videoInfos' => $videoInfos,
            'video' => $video,
            'form' => $form->createView(),
            'form2' => $form2->createView(),
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
        $form = $this->createForm(VideosType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $datas = $form->get('tags')->getData();

            for ($i = 0; $i < count($datas); $i++) {
                $video->addTag($datas[$i]);
            }
            $entityManager->persist($video);
            $entityManager->flush();

            return $this->redirectToRoute('app_videos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('videos/edit.html.twig', [
            'video' => $video,
            'form' => $form,
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
