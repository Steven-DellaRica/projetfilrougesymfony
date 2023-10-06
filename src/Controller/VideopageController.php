<?php

namespace App\Controller;

use App\Repository\VideosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
