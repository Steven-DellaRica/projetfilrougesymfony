<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminpageController extends AbstractController
{
    #[Route('/adminpage', name: 'app_adminpage')]
    public function index(): Response
    {
        return $this->render('adminpage/index.html.twig', [
            'controller_name' => 'AdminpageController',
        ]);
    }
}
