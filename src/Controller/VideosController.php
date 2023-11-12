<?php

namespace App\Controller;

use DateTime;
use App\Entity\Videos;
use App\Form\VideoIDType;
use App\Form\VideoTagsType;
use App\Service\UtilsService;
use App\Repository\VideosRepository;
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
        $url = filter_var($url, FILTER_SANITIZE_URL);

        if(filter_var($url, FILTER_VALIDATE_URL)){
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

    #[Route('/', name: 'app_videos_index', methods: ['GET'])]
    public function index(VideosRepository $videosRepository): Response
    {
        return $this->render('videos/index.html.twig', [
            'videos' => $videosRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_videos_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $partOne = true;
        $errorBool = false;
        $errorMsg = '';
        $video = new Videos();

        $form = $this->createForm(VideoIDType::class, $video);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $url = UtilsService::cleanInput($request->request->all('video_id')['video_id']);

            $videoId = $this->getYoutubeIdFromUrl($url);

            if ($videoId !== false && $videoId !== '') {

                return $this->redirectToRoute('app_videos_new_tags', ['videoId' => $videoId], Response::HTTP_SEE_OTHER);
            } else {

                $errorBool = true;
                $errorMsg = 'Le lien n\'est pas valide, ou la plateforme n\'est pas reconnue';
            }
        }

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
        $partOne = false;
        $errorBool = false;
        $errorMsg = '';
        $video = new Videos();

        $videoId = UtilsService::cleanInput($videoId);
        
        $video->setVideoId($videoId);
        $videoInfos = $this->getYoutubeInfos($videoId);
        $this->setYoutubeInfos($video, $videoInfos);

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
