<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Wallet;
use App\Entity\DispatchRecipient;
use App\Form\CreateWalletType;

/**
 * @Route("/user/wallet", name="wallet")
 */
class WalletController extends AbstractController
{
    /**
     * @Route("/create", name="create_wallet")
     */
    public function create(Request $request): Response
    {

        /* REJECT USERS NOT LOGGED IN */

        if ($this->isGranted('ROLE_USER') == false) {
            $error = new AuthenticationCredentialsNotFoundException(); // I would like to add a custom message...
            return $this->render('security/login.html.twig', ['last_username' => '', 'error' => $error]);
        }

        /* BUILD FORM DATA */
        $wallet = new Wallet();

        // create 5 recipients forms
        for ($i = 0 ; $i < 2 ; $i++) {
            $recipient = new DispatchRecipient();
            $recipient->setWallet($wallet);
            $wallet->getDispatchRecipients()->add($recipient);
        }
        $form = $this->createForm(CreateWalletType::class, $wallet);

        $form->handleRequest($request);

        //dump($wallet); die();

        $user = $this->getUser();
        
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

            // ?? link recipients to wallet

            // Save
            
            $em->persist($wallet);
            $em->flush();

            return $this->redirectToRoute('/user'); // replace by wallet view
        }

        /* DISPLAY FORM IF WROND POST REQUEST */
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
