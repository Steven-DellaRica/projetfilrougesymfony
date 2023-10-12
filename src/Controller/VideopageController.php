<?php

namespace App\Controller;

use App\Repository\VideosRepository;
use Google_Client;
use Google\Service\YouTube;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VideopageController extends AbstractController
{
    #[Route('/videopage', name: 'app_videopage')]
    public function index(VideosRepository $videosRepository): Response
    {
        

        return $this->render('videopage/index.html.twig', [
            'controller_name' => 'VideopageController',
            'videos' => $videosRepository->findAll(),
        ]);
    }

    #[Route('/videoapi', name: 'api_video')]
    public function getYoutubeInfo(Request $request, VideosRepository $videosRepository): Response
    {
        // Create and set google client
        // $client = new Google_Client();
        // $client->setDeveloperKey($apiKey);

        // // Create a youtube service Data reference
        // $youtubeDatas = new YouTube($client);
        
        // $videosArray = $videosRepository->findAll();

        // dd($videosArray);


        // // parse_str(parse_url($videoUrl, PHP_URL_QUERY), $queryParams);
        // // $videoID = $queryParams['v'];
        // // $videoTimeCode = $queryParams['t'];

        // $videoInfo = $youtubeDatas->videos->listVideos('snippet,statistics', ['id' => '3mnR8Ew7NNY']);

        // // $videos


        // dd($videoInfo);

        return $this->render('videopage/index.html.twig', [
            // 'videos' => $videosArray,
        ]);
    }
}
