<?php

namespace App\Controller\Front\TestTaker;

use App\Repository\CandidatureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class candidaturetestakerController extends AbstractController
{
    /**
     * @Route("/front/test/taker/candidaturetestaker", name="app_front_test_taker_candidaturetestaker")
     */
    public function index(): Response
    {
        return $this->render('front/test_taker/candidaturetestaker/index.html.twig', [
            'controller_name' => 'candidaturetestakerController',
        ]);
    }


/**
 * @param CandidatureRepository $repository
 * @Route("/front/testtaker/tous_condidatures", name="testtaker_tous_condidatures")
 */
public function liste(CandidatureRepository $repository): Response
{

    $role =$this->getUser()->getRoles()[0];

    $candidature=$repository->findAll();
    return $this->render('front\test_taker\candidaturetestaker\testtaker_candidature.html.twig', [
        'candidature' => $candidature,'role'=>$role
    ]);
}

}
