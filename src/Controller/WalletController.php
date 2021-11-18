<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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
    public function create(Request $request, Security $security, ValidatorInterface $validator): Response
    {
        
        /* REJECT USERS NOT LOGGED IN */
        if ($this->isGranted('ROLE_USER') == false) {
            $error = new AuthenticationCredentialsNotFoundException(); // I would like to add a custom message...
            return $this->render('security/login.html.twig', ['last_username' => '', 'error' => $error]);
        }

        /* BUILD WALLET DATA */
        $user = $security->getUser();
        $wallet = new Wallet();

        // get request content and create as much wallet recipients as sent by the user.
        try {
            $form_recipients = $request->request->get('create_wallet')['dispatchRecipients'];
        } catch (Error $e) {
            $error = new BadRequestHttpException('Wallet creation request is not correctly formatted');; // I would like to add a custom message...
            return $this->render('wallet/create_form.html.twig', ['error' => $error]);
        }
        
        // create empty form according to request and fill wallet accordingly
        for ($i = 0 ; $i < count($form_recipients) ; $i++) {
            // verify values are set
            if (empty($form_recipients[$i]['address']) || empty($form_recipients[$i]['percent'] )) {
                $error = new BadRequestHttpException('Wallet creation request is not correctly formatted');; // I would like to add a custom message...
                return $this->render('wallet/create_form.html.twig', ['error' => $error]);
            }

            $recipient = new DispatchRecipient();
            $recipient->setWallet($wallet);
            $wallet->getDispatchRecipients()->add($recipient);
        }
        $form = $this->createForm(CreateWalletType::class, $wallet);
        $form->handleRequest($request);
     
        /* MANAGE POST REQUESTS */
        if ($form->isSubmitted()) {

            $wallet->setType(1);
            $wallet->setUser($user);

            // Validate wallet
            $errors = $validator->validate($wallet);
            if (count($errors) > 0) {
                /*
                 * Uses a __toString method on the $errors variable which is a
                 * ConstraintViolationList object. This gives us a nice string
                 * for debugging.
                 */

                 // TODO return a form with a warning about the wrong value.
                $errorsString = (string) $errors;
        
                return new Response($errorsString);
            }

            // Save
            $em = $this->getDoctrine()->getManager();
            // persist each recipient
            foreach($wallet->getDispatchRecipients() as $recipient) {
                // Validate recipient
                $errors = $validator->validate($recipient);
                if (count($errors) > 0) {
                    // TODO return a form with a warning about the wrong value.
                    $errorsString = (string) $errors;
            
                    return new Response($errorsString);
                }
                $em->persist($recipient);
            }
            $em->persist($wallet);
            $em->flush();

            // get wallet id.
            $lastId = $wallet->getId();
            // TODO derive public key

            return $this->redirect('/user'); // replace by wallet view ?
        }

        return $this->redirect('/user/wallet/create');
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
