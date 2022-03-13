<?php

namespace App\Controller;
use App\Form\ForgetpassType;
use App\Form\ChangepassType;
use App\Form\UsereditType;
use App\Repository\UserRepository;
use App\Form\UserrType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Abonnement;
use App\Form\ProfilType;
use App\Form\UserType;
use App\Repository\AbonnementRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
class UserController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $repository;
    public function __construct(UserRepository $repository){
        $this->repository=$repository;
    }
    /**
     * @Route("/backindex", name="backindex")

     */
    public function dashboard(ChartBuilderInterface $chartBuilder): Response
    {
        $entreprise=$this->repository->findEntreprise();
        $testtakers=$this->repository->findTesttaker();
            $testmakers=$this->repository->findTestmaker();
$Roles=[$entreprise,$testtakers,$testmakers];

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => ['Entreprise','TestMaker','Employees'],
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [1,2,3],
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);
        return $this->render('backindex/index.html.twig', [
            'chart' => $chart, 'tm' => $testmakers, 'tt' => $testtakers, 'en' => $entreprise,
        ]);

    }
    /**
     * @Route("/allusers", name="tous_users")
     */
    public function findall(Request $request,PaginatorInterface $paginator): Response
    {
        $users=$this->repository->findAll();

        $users = $paginator->paginate(
            // Doctrine Query, not results
                $users,
                // Define the page parameter
                $request->query->getInt('page', 1),
                // Items per page
                3);

        return $this->render('back/users.html.twig', [
            'users' => $users,
        ]);
    }
    /**
     * @Route("/loginn", name="log")
     */
    public function ffff(TokenStorageInterface $tokenStorage): Response
    {
        $tokenStorage->setToken();
        $this->addFlash('success','Vous avez été bloqué par l administrateur de la plateforme TestAndHire.

 !');
        return $this->render('security/error.html.twig');
    }
    /**
     * @Route("/editeruser/{id}", name="editeruser")
     * @param User $user
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
        public function editer(User $user,Request $request,$id,EntityManagerInterface $em,UserRepository $repository){
        $user = $repository->find($id);

        $form=$this->createForm(UsereditType::class,$user);
        $form->handleRequest($request);
     
            if($form->isSubmitted() && $form->isValid()){
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
                $this->addFlash('success','user modifié avec succès!');
            return $this->redirectToRoute('tous_users');
        }
        return $this->render('back/editeruser.html.twig', array('form'=>$form->createView()));

    }
    /**
     * @Route("/removeuser/{id}", name="supprimeruser")
     * @IsGranted("ROLE_ADMIN")
     */
    public function supprimer($id,UserRepository $users,EntityManagerInterface $em){

        $users= $users->find($id);
       

        $users->setEtat("disabled");
        $em->flush();
        $this->addFlash('success','user disabled  succès!');

        return $this->redirectToRoute('tous_users');
    }
     /**
     * @Route("/debloqueruser/{id}", name="debloquer")
     * @IsGranted("ROLE_ADMIN")
     */
    public function debloquer($id,UserRepository $users,EntityManagerInterface $em){

        $users= $users->find($id);
       

        $users->setEtat("enable");
        $em->flush();
        $this->addFlash('success','user enabled avec  succès!');

        return $this->redirectToRoute('tous_users');
    }
    /**
     * @Route("/adduser", name="ajouteruser")
     */
    public function ajouter(Request $request,EntityManagerInterface $em,MailerInterface $mailer,UserPasswordHasherInterface $userPasswordHasher,AbonnementRepository $repositoryab){
        $user=new User();
        $form= $this->createForm(UserType::class,$user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
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
            $user->setEtat("enable");
            $abonnement = $repositoryab->find(1);
            $user->setAbonnement($abonnement);
            $emailTo=$form->get('email')->getData();
            $message = "Bienvenue sur test and hire \nvotre mot de passe  : " .$form->get('plainPassword')->getData() ;
            $emaill = (new Email())
                ->from($emailTo)
                ->to($emailTo)
                ->priority(Email::PRIORITY_HIGH)
                ->subject('[ Creation du mot de passe ]')
                ->text($message) ;
            $mailer->send($emaill);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $em->persist($user);
            $em->flush();
            $this->addFlash('success','user ajouté avec succès!');
            return $this->redirectToRoute('tous_users');
        }
        return $this->render("back/ajouteruser.html.twig",array('form'=>$form->createView()));
    }
    /**
     * @Route("/addtestmaker", name="ajoutertestmaker")
     * @IsGranted("ROLE_ADMIN")
     */
    public function ajoutertt(Request $request,EntityManagerInterface $em,MailerInterface $mailer, UserPasswordHasherInterface $userPasswordHasher){
        $user=new User();
        $form= $this->createForm(UserrType::class,$user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
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
            $user->setRoles(["ROLE_TESTMAKER"]);
            $user->setEtat("enable");
            $emailTo=$form->get('email')->getData();
            $message = "Bienvenue sur test and hire ".ucfirst($form->get('plainPassword')->getData())."\nvotre mot de passe  : " .$form->get('plainPassword')->getData() ;
                $emaill = (new Email())
                    ->from($emailTo)
                    ->to($emailTo)
                    ->priority(Email::PRIORITY_HIGH)
                    ->subject('[ Creation du mot de passe ]')
                    ->text($message) ;
                $mailer->send($emaill);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $em->persist($user);
            $em->flush();
            $this->addFlash('success','le test maker est ajouté avec succès!');
            return $this->redirectToRoute('tous_users');
        }
        return $this->render("back/ajoutertmaker.html.twig",array('form'=>$form->createView()));
    }



    /**
     * @Route("/modifierprofil", name="modifier_profil")
     * @param User $user
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editerUserConnecte(Request $request,EntityManagerInterface $em,UserRepository $repository){
        if ($this->getUser()){
            $id = $this->getUser()->getId();
        }
       $user = $repository->find($id);
  
        $form=$this->createForm(ProfilType::class,$user);
        $form->handleRequest($request);
        $role =$this->getUser()->getRoles()[0];

        if($form->isSubmitted()  && $form->isValid() ){
      
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
     * @Route("/Forgotten", name="forget_password")
     */
    public function forgottenPassword(MailerInterface $mailer,Request $request )
    {
        $mail = $request->get('email');
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->findBy(array('email'=>$mail));
        dd($user);
        $emailTo= $user[0]->getEmail();
        $random = random_int(159847, 985623);

        //$url = $this->generateUrl('app_reset_password', array('token'=>$token), UrlGeneratorInterface::ABSOLUTE_URL);
        $message = "Bienvenue ".ucfirst($user[0]->getEmail())."\nreinstaler mdp : " .$random ;
        if($mail==$emailTo)
        {
            $email = (new Email())
                ->from($emailTo)
                ->to($emailTo)
                ->priority(Email::PRIORITY_HIGH)
                ->subject('[ Rest password ]')
                ->text($message) ;
            $mailer->send($email);
            return new JsonResponse("ok", 200);
        }
    }
    /**
      * @Route("/forgetpass", name="for_pass")
     */
    public function ff(Request $request,MailerInterface $mailer,UserPasswordHasherInterface $userPasswordHasher,EntityManagerInterface $em){
        $user=new User();
        $form= $this->createForm(ForgetpassType::class,$user);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $email =$request->request->get('forgetpass')['email'];

            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findBy(array('email'=>$email));
            if(!$user){

                $this->addFlash('success','il n ya pas un user avec ce mail');
                return $this->render('security/forgetpass.html.twig',array('form'=>$form->createView()));
            }
            $emailTo= $user[0]->getEmail();
            $em->flush();
            $url = $this->generateUrl('mot_de_passe', array('email'=>$email), UrlGeneratorInterface::ABSOLUTE_URL);

            $message = "Bienvenue ".ucfirst($user[0]->getEmail())."\nveuillez cliquer sur ce lien pour changer votre mot de passe : ".$url  ;
            if($email==$emailTo)
            {
                $emaill = (new Email())
                    ->from($emailTo)
                    ->to($emailTo)
                    ->priority(Email::PRIORITY_HIGH)
                    ->subject('[ changer le mot de passe ]')
                    ->text($message) ;
                $mailer->send($emaill);
                $this->addFlash('ss','Success : un mail vous a été envoyé , vous pouvez maintenant se connecter');

               return $this->redirectToRoute('for_pass');
            }
            //  $this->addFlash('success','user ajouté avec succès!');

        }
        return $this->render('security/forgetpass.html.twig',array('form'=>$form->createView()));
    }
    /**
     * @Route("/changepass/{email}", name="mot_de_passe")
     */
    public function change(EntityManagerInterface $em,UserPasswordHasherInterface $userPasswordHasher,
                                      Request $request,$email )
    {
        $email = $request->get('email');
        $user = $em->getRepository(User::class)->findBy(array('email'=>$email));

        $form= $this->createForm(ChangepassType::class,$user[0]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $password =$request->request->get('changepass')['plainPassword'];

        $user[0]->setPassword(
          $userPasswordHasher->hashPassword(
                $user[0],
             $password
           )
        );

        $em->flush();
        return $this->redirectToRoute('app_login');
        }
        return $this->render('security/changepass.html.twig',array('form'=>$form->createView()));
    }
    /**
     * @Route("/searchuser/", name="search-user")
     */
    public function searchuser(UserRepository $userrepository, Request $request)
    {
        $users = $userrepository->findByNamePopular(
            $request->query->get('query')
        );

        $entityManager = $this->getDoctrine()->getManager();
        $categoryRepository=$entityManager->getRepository(User::class);


        return $this->render('back/users.html.twig', [

            'users'=>$users,

        ]);}
}