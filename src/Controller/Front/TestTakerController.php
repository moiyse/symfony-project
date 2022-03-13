<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestTakerController extends AbstractController
{
    /**
     * @Route("/front/test/taker", name="app_front_test_taker")
     */
    public function index(): Response
    {
        return $this->render('front/test_taker/index.html.twig', [
            'controller_name' => 'TestTakerController',
        ]);
    }
}
