<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserPageController extends AbstractController
{
    #[Route('/userpage/{id}', name: 'app_user_page')]
    public function index(UserRepository $userRepo, int $id): Response
    {
        $errorBool = false;
        $errorMessage = "";
        $user = $this->getUser();

        if($user !== null){
            if($user->getId() !== $id){
                $errorBool = true;
                $errorMessage = "User is not correct !";
            }
        } else {
            $errorBool = true;
            $errorMessage = "Please Login first !";
        }
        
        return $this->render('user_page/index.html.twig', [
            'user' => $user,
            'errorBool' => $errorBool,
            'errorMessage' => $errorMessage
        ]);
    }
}
