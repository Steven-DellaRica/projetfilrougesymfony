<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\TagsRepository;
use App\Service\UtilsService;
use App\Repository\VideosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/videopage')]
class VideopageController extends AbstractController
{
    #[Route('/', name: 'app_videopage', methods: ['GET', 'POST'])]
    public function index(Request $request, VideosRepository $videosRepository, TagsRepository $tagsRepository): Response
    {
        $form = $this->createForm(SearchType::class);

        $videosDisplay = $videosRepository->findAll();

        // dd($request);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->getData()['searchBarTags'] === null && $form->getData()['searchBarAuthors'] === null) {
                $videosDisplay = $videosRepository->findAll();
            } else {

                // Pour être sur d'avoir des variables utilisables
                if ($form->getData()['searchBarTags'] !== null) {
                    $searchTags = explode(" ", UtilsService::cleanInput($form->getData()['searchBarTags']));
                } else {
                    $searchTags = [];
                }
                if ($form->getData()['searchBarAuthors'] !== null) {
                    $searchAuthors = explode(" ", UtilsService::cleanInput($form->getData()['searchBarAuthors']));
                } else {
                    $searchAuthors = [];
                }

                $searchInput = array_merge($searchTags, $searchAuthors);

                foreach ($searchInput as $tag) {
                    // echo '<pre>' . var_export($tag, true) . '</pre>';
                    $tagArray[] = $tagsRepository->findBy(['tags_libelle' => $tag]);
                }
                
                $vide = true;

                foreach ($tagArray as $index => $tag){
                    if(!empty($tag)){
                        $vide = false;
                    } else {
                        unset($tagArray[$index]);
                        $tagArray = array_values($tagArray);
                    }
                }

                if($vide === true){
                    echo '<p>Aucun tag ne correspond à cette recherche</p>';
                } else {
                    foreach ($tagArray as $tag) {
                        // echo '<pre> ' . var_export($tag[0]->getId(), true) . '</pre>';
                        $tagId[] = $tag[0]->getId();
                    }
                    foreach($tagId as $tag){
                        $tempVideosDisplay[] = $videosRepository->getVideoTags($tag);
                    }
                    $videosDisplay = array_merge(...$tempVideosDisplay);
                    
                    foreach ($videosDisplay as $videoId){
                        echo '<pre> ' . var_export($videoId['video_id'], true) . '</pre>';
                        if(empty($videosRepository->find($videoId['video_id']))){
                            echo '<pre>La vidéo n existe pas ' . var_export($videoId['video_id'], true) . '</pre>';
                        } else {
                            $tempVideosDisplay2[] = $videosRepository->find($videoId['video_id']);
                        }
                    }
                    $videosDisplay = $tempVideosDisplay2;
                    
                    // dd($videosDisplay);
                }

                
                
                
                // foreach($searchInput as $tags){
                //     // $tagsId[] = $tagsRepository->findOneBy(['tags_libelle' => $tags]);

                //     $videosDisplay = $videosRepository->findBy(['tags' => $tags]);
                // }


                // $videosDisplay = $videosRepository->findBy($searchInput('tags') ->)

                // dd($videosDisplay);
                //Utiliser les searchInputs pour aller chercher les éléments dans la BDD

            }
        }

        return $this->render('videopage/index.html.twig', [
            'videos' => $videosDisplay,
            'form' => $form->createView(),
        ]);
    }
}
