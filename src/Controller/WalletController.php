<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

use App\Entity\Wallet;
use App\Entity\User;
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
    public function create(Request $request, Security $security, ValidatorInterface $validator): Response
    {
        /* REJECT USERS NOT LOGGED IN */
        if ($this->isGranted('ROLE_USER') == false) {
            $error = new AuthenticationCredentialsNotFoundException(); // I would like to add a custom message...
            return $this->render('security/login.html.twig', ['last_username' => '', 'error' => $error]);
        }

        /* BUILD WALLET DATA */
        $wallet = new Wallet();

        switch ($request->getMethod()) {
            case "POST":
                $user = $security->getUser();
                $form = self::handlePostForm($request, $wallet, $user);
                break;
            default:
                $form = self::createEmptyForm($wallet);
                break;
        }

        $form->handleRequest($request, $wallet);

        /* SAVE VALID FORMS */
        if ($form->isSubmitted() && $form->isvalid()) {
            $em = $this->getDoctrine()->getManager();
            // persist each recipient
            foreach($wallet->getDispatchRecipients() as $recipient) {
                $em->persist($recipient);
            }
            $em->persist($wallet);
            $em->flush();

            // get wallet id.
            $lastId = $wallet->getId();
            // TODO derive public key

            return $this->redirect('/user'); // replace by wallet view ?
        }

        return $this->render('wallet/create_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    private function handlePostForm(Request $request, Wallet $wallet, User $user) {

        // get request content.
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
        $wallet->setType(Wallet::$TYPE_SPREAD);
        $wallet->setState(Wallet::$STATE_ACTIVE);
        $wallet->setUser($user);

        return $form;
    }

    private function createEmptyForm(Wallet $wallet) {
        $recipient = new DispatchRecipient();
        $recipient->setWallet($wallet);
        $wallet->getDispatchRecipients()->add($recipient);

        return $this->createForm(CreateWalletType::class, $wallet);
    }

    /**
     * @Route("/{id}", name="view_wallet", methods={"GET"})
     */
    public function show(int $id, Security $security): Response
    {
        /* REJECT USERS NOT LOGGED IN */
        if ($this->isGranted('ROLE_USER') == false) {
            $error = new AuthenticationCredentialsNotFoundException(); // I would like to add a custom message...
            return $this->render('security/login.html.twig', ['last_username' => '', 'error' => $error]);
        }

        /* THIS WALLET BELONGS TO THE CURRENT USER */
        $user = $security->getUser();
        try {
            $wallet = $user->getSingleWallet($id);
        } catch (Exception $e) {
            $error = new AccessDeniedException(); // I would like to add a custom message...
            return $this->render('security/login.html.twig', ['last_username' => '', 'error' => $error]);
        }

        /* DISPLAY WALLET PAGE */
        return $this->render('wallet/show.html.twig', [
            'wallet' => $wallet,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete_wallet", methods={"GET"})
     */
    public function delete(int $id, Security $security): Response
    {
        /* REJECT USERS NOT LOGGED IN */
        if ($this->isGranted('ROLE_USER') == false) {
            $error = new AuthenticationCredentialsNotFoundException(); // I would like to add a custom message...
            return $this->render('security/login.html.twig', ['last_username' => '', 'error' => $error]);
        }

        /* THIS WALLET BELONGS TO THE CURRENT USER */
        $user = $security->getUser();
        try {
            $wallet = $user->getSingleWallet($id);
        } catch (Exception $e) {
            $error = new AccessDeniedException(); // I would like to add a custom message...
            return $this->render('security/login.html.twig', ['last_username' => '', 'error' => $error]);
        }

        /* MARK WALLET TO BE REMOVED */
        // Blockchain backend will refund the user before removing it.
        $wallet->setState(Wallet::$STATE_TO_REMOVE);
        $em = $this->getDoctrine()->getManager();
        $em->persist($wallet);
        $em->flush();

        /* DISPLAY WALLET PAGE */
        return $this->render('wallet/show.html.twig', [
            'wallet' => $wallet,
        ]);
    }

}
