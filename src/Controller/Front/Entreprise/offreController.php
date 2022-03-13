<?php

namespace App\Controller\Front\Entreprise;

use App\Form\OffreType;
use App\Entity\User;
use App\Repository\OffreRepository;
use Doctrine\DBAL\Types\DateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Offre;
use App\Repository\UserRepository;

class offreController extends AbstractController
{
    /**
     * @Route("/front/creeroffre", name="front_entreprise_creroffre")
     */
    public function index(): Response
    {
        return $this->render('front/entreprise/offre/index.html.twig', [
            'controller_name' => 'offreController',
        ]);
    }






    /**
     * @Route("/front/mesofffres", name="front_entreprise_mesoffres")
     */
    public function ddddd(): Response
    {
        return $this->render('front/entreprise/offre/offres.html.twig', [
            'controller_name' => 'offreController',
        ]);
    }




    /**
     * @param OffreRepository $repository
     * @Route("/front/tousofffres", name="tous_offres")
     */
    public function liste(OffreRepository $repository): Response
    {
        
        $role =$this->getUser()->getRoles()[0];
        $Offre=$repository->findAll();
        return $this->render('front/entreprise/offre/tousoffres.html.twig', [
            'Offre' => $Offre,'role' => $role
        ]);
    }

    /**
     * @param OffreRepository $repository
     * @Route("/front/alloffres", name="tous_les_offres")
     */
    public function offres(OffreRepository $repository,UserRepository $us): Response
    {
        
        $role =$this->getUser()->getRoles()[0];
        $ab= $this->getUser()->getAbonnement()->getId();
        $Offre=$repository->findAll();
        return $this->render('front/test_taker/abonnement/postuleroffre.html.twig', [
            'Offre' => $Offre,'role' => $role , 'ab'=>$ab
        ]);
    }

    /**
     * @param OffreRepository $repository
     * @Route("/front/tousofffres2/{id}", name="tous_offres2")
     */
    public function liste2(OffreRepository $repository,$id): Response
    {

        $Offre=$repository->find($id);
        return $this->render('front/entreprise/offre/tousoffres.html.twig', [
            'Offre2' => $Offre,
        ]);
    }






    /**
     * @Route("/front/new", name="ajout_offre")
     */
    public function createAction(Request $request) {

        $Offre = new Offre();
        $user= $this->getUser();
    
        $form = $this->createForm(OffreType::class,$Offre);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        $role =$this->getUser()->getRoles()[0];
        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $Offre->setUser($user);
            $em->persist($Offre);
            $em->flush();
            return $this->redirectToRoute('tous_offres');
        }
        return $this->render("front/entreprise/offre/add.html.twig", ['form' => $form->createView(),'role' => $role]);

    }






    /**
     * @Route("offre/edit/{id}", name="edit_offre")
     */
    public function edit(OffreRepository $repository,$id, Request $request ):response
    {
        $Offre=$repository->find($id);
        $role =$this->getUser()->getRoles()[0];
       
        $form=$this->createForm(OffreType::class, $Offre);
        $form->add('update',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() )
        {
            $em=$this->getDoctrine()->getManager();

            $em->flush();
            return $this->redirectToRoute('tous_offres');
        }
        return $this->render('front/entreprise/offre/edit.html.twig',[

            'form' =>$form->createView(),'role' => $role
        ]);
    }

    /**
     * @Route("/deleteoff/{id}", name="supprimer_offre")
     */
    public function deleteClass($id)
    {

        $em = $this->getDoctrine()->getManager();
        $Offre = $this->getDoctrine()->getRepository(Offre::class)->find($id);
        $em->remove($Offre);
        $em->flush();
        return $this->redirectToRoute("tous_offres");

    }



    /**
     * @Route("/search", name="offre-serie-search")
     */
    public function searchSeries(OffreRepository $testrepository, Request $request)
    {

        $role =$this->getUser()->getRoles()[0];

        $tests = $testrepository->findByNamePopular(
            $request->query->get('query')
        );

        $entityManager = $this->getDoctrine()->getManager();
        $categoryRepository=$entityManager->getRepository(Offre::class);


        return $this->render('front/entreprise/offre/tousoffres.html.twig', [
            'controller_name' => 'offreController',
            'Offre'=>$tests,
            'role'=>$role

        ]);}



}