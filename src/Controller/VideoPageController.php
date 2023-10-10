<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VideoPageController extends AbstractController
{
    #[Route('/videopage', name: 'app_videopage')]
    public function index(): Response
    {
        return $this->render('video_page/index.html.twig', [
            'controller_name' => 'VideoPageController',
        ]);
    }
}
