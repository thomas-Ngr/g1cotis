<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use  Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Core\Security;

use App\Entity\Wallet;
use App\Entity\DispatchRecipient;
use App\Form\CreateWalletType;



/**
 * @Route("/user/wallet", name="wallet")
 */
class WalletController extends AbstractController
{
    /**
     * @Route("/create", name="create_wallet", methods={"POST"})
     */
    public function create(Request $request, Security $security): Response
    {
        
        /* REJECT USERS NOT LOGGED IN */
        if ($this->isGranted('ROLE_USER') == false) {
            $error = new AuthenticationCredentialsNotFoundException(); // I would like to add a custom message...
            return $this->render('security/login.html.twig', ['last_username' => '', 'error' => $error]);
        }

        /* BUILD FORM DATA */
        $user = $security->getUser();
        $wallet = new Wallet();

        // get request content and create as much wallet recipients as sent by the user.
        try {
            $form_recipients = $request->request->get('create_wallet')['dispatchRecipients'];
        } catch (Error $e) {
            $error = new BadRequestHttpException('Wallet creation request is not correctly formatted');; // I would like to add a custom message...
            return $this->redirectToRoute('/create');
        }
        
        for ($i = 0 ; $i < count($form_recipients) ; $i++) {
            $recipient = new DispatchRecipient();
            $recipient->setWallet($wallet);
            $wallet->getDispatchRecipients()->add($recipient);
        }
    
        $form = $this->createForm(CreateWalletType::class, $wallet);
        $form->handleRequest($request);
        
        /* MANAGE POST REQUESTS */
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            // persist each recipient
            foreach($wallet->getDispatchRecipients() as $recipient) {
                $em->persist($recipient);
            }

            // wallet type (1 for dispatch. Should go in a constant.)
            $wallet->setType(1);

            // link wallet to user
            $wallet->setUser($user);

            // Save
            $em->persist($wallet);
            $em->flush();

            return $this->redirect('/user'); // replace by wallet view ?
        }

        // TODO deal with wrong POST requests
    }

    /**
     * @Route("/create", name="create_wallet_form", methods={"GET","HEAD"})
     */
    public function displayForm(Request $request, Security $security): Response
    {

        /* REJECT USERS NOT LOGGED IN */    
        if ($this->isGranted('ROLE_USER') == false) {
            $error = new AuthenticationCredentialsNotFoundException(); // I would like to add a custom message...
            return $this->render('security/login.html.twig', ['last_username' => '', 'error' => $error]);
        }

        /* BUILD FORM DATA */
        $user = $security->getUser();
        $wallet = new Wallet();

        $recipient = new DispatchRecipient();
        $recipient->setWallet($wallet);
        $wallet->getDispatchRecipients()->add($recipient);

        $form = $this->createForm(CreateWalletType::class, $wallet);

        //dump($form); die(); // debug

        /* DISPLAY FORM */
        return $this->render('wallet/create_form.html.twig', [
            'controller_name' => 'Create wallet',
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="view_account")
     */
    public function show(): Response
    {
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }
}
