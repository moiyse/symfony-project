<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UsereditType;
use App\Form\UserrType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
class UserjsonController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $repository;
    public function __construct(UserRepository $repository){
        $this->repository=$repository;
    }
    /**
     * @Route("/json/allusers", name="tous_users_json")
     * @IsGranted("ROLE_ADMIN")
     */
    public function findall(NormalizerInterface $normalizer): Response
    {
        $users=$this->repository->findAll();
        $jsonContent = $normalizer->normalize($users, 'json',['groups'=> 'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/json/editeruser/{id}", name="editeruser_json")
     * @param User $user
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function editer(User $user,Request $request,$id,NormalizerInterface $normalizer,EntityManagerInterface $em,UserRepository $repository){
        $user = $repository->find($id);
        $user->setEmail($request->get('email'));
        $user->setRoles($request->get('roles'));
        $user->setImage($request->get('image'));
        $em->flush();
        $jsonContent=$normalizer->normalize($user,'json',['groups'=>'post:read']);
        return new Response("user modifié avec succès".json_encode($jsonContent));


    }

    /**
     * @Route("/json/add", name="ajouteruser_json")
     * @IsGranted("ROLE_ADMIN")
     */
    public function ajouter(NormalizerInterface $normalizer ,Request $request,EntityManagerInterface $em,UserPasswordHasherInterface $userPasswordHasher){
        $user=new User();

        $user->setEmail($request->get('email'));
        $user->setRoles($request->get('roles'));
        $user->setImage($request->get('image'));
        $user->setPassword($request->get('password'));
        $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $request->get('password')
                )
            );
            $em->persist($user);
            $em->flush();
        $jsonContent=$normalizer->normalize($user,'json',['groups'=>'post:read']);
        return new Response("user ajouté avec succès".json_encode($jsonContent));
    }
    /**
     * @Route("/json/addtestmaker", name="ajoutertestmaker_json")
     * @IsGranted("ROLE_ADMIN")
     */
    public function ajoutertt(NormalizerInterface $normalizer,Request $request,UserRepository $users,EntityManagerInterface $em,UserPasswordHasherInterface $userPasswordHasher){
        $user=new User();
        $user->setEmail($request->get('email'));
        $user->setRoles($request->get('roles'));
        $user->setImage($request->get('image'));
        $id = $request->get('user_id');
        $entreprise= $users->find($id);
        $user->setUser( $entreprise);
        $user->setPassword($request->get('password'));
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $request->get('password')
            )
        );
            $em->persist($user);
            $em->flush();
        $jsonContent=$normalizer->normalize($user,'json',['groups'=>'post:read']);
        return new Response("user ajouté avec succès".json_encode($jsonContent));
    }



    /**
     * @Route("/json/modifierprofil", name="json_modifier_profil")
     * @param User $user
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editerUserConnecte(Request $request,EntityManagerInterface $em,UserRepository $repository){
        if ($this->getUser()){
            $id =$this->getUser()->getId();
        }
        $user = $repository->find($id);
        $form=$this->createForm(UsereditType::class,$user);
        $form->handleRequest($request);
        $role =$this->getUser()->getRoles()[0];

        if($form->isSubmitted() && $form->isValid() ){
            $file =$user->getImage();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $user->setImage($fileName);
            $em->flush();
            return $this->redirectToRoute('modifier_profil');
        }
        return $this->render('front/entreprise/profil/index.html.twig', [
            'role' => $role, 'form' => $form->createView(),'user'=>$user
        ]);
    }

    /**
     * @Route("/api/register", name="register_api")
     */
    public function register(NormalizerInterface $normalizer ,Request $request,EntityManagerInterface $em,UserPasswordHasherInterface $userPasswordHasher){

        $email = $request->query->get('email');
        $password = $request->query->get('password');
        $image=  $request->query->get('image');
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            return new Response("email invalid");
        }
        $user=new User();
        $user->setEmail($email);
        $user->setRoles(["ROLE_TESTTAKER"]);
        $user->setImage($image);
        $user->setPassword($password);
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $password
            )
        );
        try {
            $em->persist($user);
            $em->flush();
            return new Response("user ajouté avec succès" , 200);
        }catch(\Exception $ex)
        {
            return new Response("exception ".$ex->getMessage(), 404);
        }
    }
    /**
     * @Route("/api/login", name="login_api")
     */
    public function login(NormalizerInterface $normalizer ,Request $request,EntityManagerInterface $em,UserPasswordHasherInterface $userPasswordHasher,UserRepository $repository){

        $email = $request->query->get('email');
        $password = $request->query->get('password');
        $user = $repository->findOneBy(['email'=> $email]);
        if ($user){
            if(password_verify($password,$user->getPassword())){
                $normalizers = [new ObjectNormalizer()];
                $serializer = new Serializer($normalizers);

                $formatted = $serializer-> normalize($user);
                return new JsonResponse($formatted);
            }else{
                return new Response( "password not found ");
            }
        }
        else{
            return new Response( "user not found ");
        }
    }

}
