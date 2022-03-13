<?php

namespace App\Controller\Front\Testmaker;

use App\Entity\Sujet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\TestRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\TestType;
use App\Entity\Test;
use App\Form\SujetType;
use App\Repository\CandidatureRepository;
use App\Repository\QuestionRepository;
use Knp\Component\Pager\PaginatorInterface;

class testController extends AbstractController
{
    /**
     * @Route("/front/testmaker/test", name="front_testmaker_test")
     */
    public function index(): Response
    {
        return $this->render('front/testmaker/test/index.html.twig', [
            'controller_name' => 'testController',
        ]);
    }


    /**
     * @param TestRepository $repository
     * @param QuestionRepository $repository
     * @Route("/front/testmaker/alltests", name="all_tests")
     */
    public function liste(QuestionRepository $question_repository,TestRepository $repository,Request $request, PaginatorInterface $paginator): Response
    {

        $role =$this->getUser()->getRoles()[0];
        $tests = $repository->Sujetall();
        

        $tests = $paginator->paginate(
        // Doctrine Query, not results
            $tests,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            3);

        return $this->render('front\testmaker\test\alltests.html.twig', [
            'alltests' => $tests,'role'=>$role
        ]);
    }

    

    /**
     * @Route("front/testmaker/test/edittest/{id}", name="edit_test")
     */
    public function edit(TestRepository $repository,$id, Request $request ):response
    {
        $Test=$repository->find($id);
        $role =$this->getUser()->getRoles()[0];

        $form=$this->createForm(TestType::class, $Test);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('all_tests');
        }
        return $this->render('front\testmaker\test\edittest.html.twig',[

            'form' =>$form->createView(),'role'=>$role
        ]);
    }

    /**
     * @param CandidatureRepository $repository
     * @Route("front/testmaker/test/addtest/{id_cand}", name="add_test")
     */
    public function createAction(Request $request,CandidatureRepository $candidature_repository,$id_cand) {
    $candidature = $candidature_repository->find($id_cand);

        $Test = new test();
        

        
        $role =$this->getUser()->getRoles()[0];

        $form = $this->createForm(TestType::class,$Test);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $Test->setCandidature($candidature);
            $em->persist($Test);
            $candidature->setEtatCandidature("test a ete cree supprimer svp");

        //    $Test->setCandidature($candidature);

            $em->flush();

            $this->addFlash(
                'info',
                'test ajouter'
            );

            return $this->redirectToRoute('all_tests');
        }
        return $this->render('front\testmaker\test\addtest.html.twig', ['form' => $form->createView(),'role'=>$role]);

    }

    /**
     * @Route("front/testmaker/test/deletetest/{id}", name="delete_test")
     */
    public function deleteClass($id)
    {
        

        $em = $this->getDoctrine()->getManager();
        $Test = $this->getDoctrine()->getRepository(Test::class)->find($id);
        $em->remove($Test);
        $em->flush();
        return $this->redirectToRoute("all_tests");

    }

    /**
     * @Route("front/testmaker/test/rienderoute", name="next_test")
     */
    public function rienderoute()
    {
        return $this->render('front\testmaker\test\next.html.twig');

    }


/**
 * @Route("front/testmaker/test/addsujet", name="add_sujet")
 */
public function addsujet(Request $request) {

    $Sujet = new sujet();
    $role =$this->getUser()->getRoles()[0];

    $form = $this->createForm(SujetType::class,$Sujet);
    $form->add('suivant',SubmitType::class);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
        $em = $this->getDoctrine()->getManager();
        $em->persist($Sujet);
        $em->flush();

        return $this->redirectToRoute('all_tests');
    }
    return $this->render('front\testmaker\test\next.html.twig', ['form' => $form->createView(),'role'=>$role]);

}
    /**
     * @param TestRepository $repository
     * @Route("/back/admin/alltests", name="test1")
     */
    public function liste1(TestRepository $repository): Response
    {
        $role =$this->getUser()->getRoles()[0];

        $tests = $repository->Sujetall();

        return $this->render('back\tests.html.twig', [
            'alltests' => $tests,'role'=>$role
        ]);
    }



    /**
     * @Route("/test_search/", name="test_search")
     */
    public function searchSeries(TestRepository $testrepository, Request $request)
    {
        $role =$this->getUser()->getRoles()[0];

        $tests = $testrepository->findByNamePopular(
            $request->query->get('query')
        );

        $entityManager = $this->getDoctrine()->getManager();
        $categoryRepository=$entityManager->getRepository(Test::class);
        $categories=$categoryRepository->findAll();

        return $this->render('back\tests.html.twig', [
            'controller_name' => 'testController',
            'alltests'=>$tests,'role'=>$role

        ]);
    }

}

