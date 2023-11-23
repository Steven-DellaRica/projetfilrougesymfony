<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\VideosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/videopage')]
class VideopageController extends AbstractController
{
    #[Route('/', name: 'app_videopage', methods: ['GET', 'POST'])]
    public function index(Request $request, VideosRepository $videosRepository): Response
    {
        // $searchData = new ;

        $form = $this->createForm(SearchType::class);

        // dd($request);

        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()){
            dd($form->getData());
        }

        return $this->render('videopage/index.html.twig', [
            'videos' => $videosRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }
}
