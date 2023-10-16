<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\VideosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/videopage')]
class VideopageController extends AbstractController
{
    #[Route('/', name: 'app_videopage', methods: ['GET'])]
    public function index(VideosRepository $videosRepository, UserRepository $userRepository): Response
    {
        return $this->render('videopage/index.html.twig', [
            'videos' => $videosRepository->findAll(),
        ]);
    }
}
