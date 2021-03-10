<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgotPwdType;
use App\Form\CheckEmailType;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{   

 /**
     * Encodeur de mot de passe
     *
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

        /**
     * Undocumented variable
     *
     * @var TranslatorInterface
     */
    private $translator;


    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
       // Si l'utilisateur est deja connecté le rediriger vers la page listing
        if ($this->getUser()) {
            return $this->redirectToRoute('tasks_listing');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    /**
     * 
     * @Route("/checkEmail", name="app_checkEmail")
     */
    public function checkEmail(Request $request, MailerInterface $mailer)
    {
        $form = $this->createform(CheckEmailType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() and $form-> isValid()){
            $manager = $this->getDoctrine()->getManager();

            $userEmail = $form['email']->getData();
            $existEmail = $this->getDoctrine()->getRepository(User::class)->findOneBy(array('email' => $userEmail));
    //dd($existEmail);

            // On génère un token et on l'enregistre
            $existEmail->setToken(md5(uniqid()));

            // On génère la date 
            $existEmail->setPwdRequestedAt(new \Datetime());
    // dd($existEmail);

            $manager->persist($existEmail);
            $manager->flush();

    // Essai d'envoi de mail

            if($existEmail){
                $message = (new Email())
                    ->to($userEmail)
                    ->html(
                        $this->renderView(
                            'emails/changePwd.html.twig', ['token'=>$existEmail->getToken()]
                        )
                    )
                  ;
            $mailer->send($message);      

                return $this->redirectToRoute('app_forgotPwd', ['token'=>$existEmail->getToken()]);
            }
        }
        return $this->render('security/checkEmail.html.twig', ['form' => $form->createView()]);
    }


    // si supérieur à 10min, retourne false 
    private function isRequestInTime(\Datetime $pwdRequestedAt = null)
    {
        if ($pwdRequestedAt === null)
        {
            return false;        
        }
        
        $now = new \DateTime();
        $interval = $now->getTimestamp() - $pwdRequestedAt->getTimestamp();

        $daySeconds = 60 * 0.5;
        $response = $interval > $daySeconds ? false : $response = true;
        return $response;
    }
    
    /**
     * @Route("/forgotPwd/{token}", name="app_forgotPwd")
     * 
     */
    public function forgotPwd(User $id, $token, Request $request)
    {

        if($id->getToken() === null || $token !== $id->getToken() || !$this->isRequestInTime($id->getPwdRequestedAt())){

            throw new AccessDeniedHttpException();
        }

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id'=> $id]);
//  dd($user);
        $form = $this->createform(ForgotPwdType::class);
        $form->handleRequest($request);


        if($form->isSubmitted() and $form-> isValid()){
            $manager = $this->getDoctrine()->getManager();

            $hash = $this->encoder->encodePassword($user, $form['password']->getdata());
//dd($hash);
            $user ->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/forgotPwd.html.twig', ['form' => $form->createView()]);
    }
    
}
