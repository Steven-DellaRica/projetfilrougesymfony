<?php

namespace App\Controller;

use App\Entity\Tags;
use App\Entity\Videos;
use App\Form\VideosType;
use App\Repository\VideosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/videos')]
class VideosController extends AbstractController
{
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
        $video = new Videos();
        $form = $this->createForm(VideosType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($video);
            $entityManager->flush();

            return $this->redirectToRoute('app_videos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('videos/new.html.twig', [
            'video' => $video,
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
        if ($this->isCsrfTokenValid('delete'.$video->getId(), $request->request->get('_token'))) {
            $entityManager->remove($video);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_videos_index', [], Response::HTTP_SEE_OTHER);
    }
}
