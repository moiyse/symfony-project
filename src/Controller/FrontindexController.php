<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontindexController extends AbstractController
{
    /**
     * @Route("/frontindex", name="frontindex")
     */
    public function index(): Response
    {
        if ($this->getUser()){
            $role =$this->getUser()->getRoles()[0];
        }
     else{
         $role="client";
     }

        return $this->render('frontindex/index.html.twig', [
            'role' => $role,
        ]);
    }
    /**
     * @Route("/getrole", name="getrole")
     */
    public function getrole(): Response
    {
         $role =$this->getUser()->getRoles()[0];
        return $this->render('frontindex/index.html.twig', [
            'role' => $role,
        ]);
    }

}
