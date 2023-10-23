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

    public function setYoutubeInfos(Videos $video, array $youtubeInfos){
        $video->setVideoTitle($youtubeInfos['snippet']['title']);
        $video->setVideoAuthor($youtubeInfos['snippet']['channelTitle']);
        $video->setVideoViews($youtubeInfos['statistics']['viewCount']);
        $dateString = $youtubeInfos['snippet']['publishedAt'];
        $videoDateConverted = new DateTime($dateString);
        // dd($youtubeInfos['snippet']['publishedAt']);
        $video->setVideoDate($videoDateConverted);
        $video->setVideoThumbnail($youtubeInfos['snippet']['thumbnails']['high']['url']);

        return $video;
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
        $videoInfos = null;
        $video = new Videos();
        $form = $this->createForm(VideoIDType::class, $video);
        $form2 = $this->createForm(VideoTagsType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $displayInfos = true;
            $videoID = $form->get('video_id')->getData();
            $videoInfos = $this->getYoutubeInfos($videoID);

            $form2->handleRequest($request);

            if ($form2->isSubmitted() && $form2->isValid()) {

                $this->setYoutubeInfos($video, $videoInfos);
    
                $datas = $form2->get('tags')->getData();
    
                for ($i = 0; $i < count($datas); $i++) {
                    $video->addTag($datas[$i]);
                }
    
                $entityManager->persist($video);
                $entityManager->flush();
    
                return $this->redirectToRoute('app_videos_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('videos/new.html.twig', [
            'displayInfos' => $displayInfos,
            'videoInfos' => $videoInfos,
            'video' => $video,
            'form2' => $form2,
            'form' => $form,
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
