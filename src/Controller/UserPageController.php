<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use  Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Core\Security;

class UserPageController extends AbstractController
{
    /**
     * @Route("/user", name="user_page")
     */
    public function userPage(Security $security): Response
    {
        if ($this->isGranted('ROLE_USER') == false) {
            $error = new AuthenticationCredentialsNotFoundException(); // I would like to add a custom message...
            return $this->render('security/login.html.twig', ['last_username' => '', 'error' => $error]);
        }

        $user = $security->getUser();
        //dump($user); die();

        if ($user) {
            return $this->render('user_page/index.html.twig', [
                'username' => $user->getUsername(),
                'email' => $user->getEmail()
                // TODO pass user accounts

                // should I pass whole user entity ?
            ]);
        }
        
        // should I add a redirection to homepage in case of error ?
    }
}
