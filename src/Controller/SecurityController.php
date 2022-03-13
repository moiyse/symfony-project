<?php

namespace App\Controller;

use App\Entity\User;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface ;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SecurityController extends AbstractController
{
    public function __invoke(User $data)
    {
        $this->validator->validate($data);
        return $data;
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils,ValidatorInterface $validator): Response
    {

        if ($this->getUser()) {
            return $this->redirectToRoute('target_path');
         }

        // get the login error if there is one

        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
      // if (count($errors) > 0){
//           return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'errors' => $errors]);

      // }
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    /**
     * @Route("/2fa", name="2fa_login")
     */
    public function check2fa( GoogleAuthenticatorInterface $authenticator ,TokenStorageInterface $storage )
    {
        $Code = $authenticator->getQRContent($storage->getToken()->getUser());
        $qrCode = "https://chart.googleapis.com/chart?cht=qr&chs=150x150&chl=".$Code;
        dd($qrCode);
        return $this->render('security/2fa_login.html.twig',[
            'qrCode' =>$qrCode
        ]);
    }
}
