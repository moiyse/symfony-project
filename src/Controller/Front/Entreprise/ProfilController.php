<?php

namespace App\Controller\Front\Entreprise;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    /**
     * @Route("/front/monprofil", name="front_entreprise_profil")
     */
    public function index(): Response
    {
        if ($this->getUser()){
            $role =$this->getUser()->getRoles()[0];
        }
        else{
            $role="client";
        }
        return $this->render('front/entreprise/profil/index.html.twig', [
            'role' => $role,
        ]);
    }
}
