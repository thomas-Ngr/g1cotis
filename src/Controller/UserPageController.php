<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserPageController extends AbstractController
{
    /**
     * @Route("/user/page", name="user_page")
     */
    public function index(): Response
    {
        return $this->render('user_page/index.html.twig', [
            'controller_name' => 'UserPageController',
        ]);
    }
}
