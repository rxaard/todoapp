<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgotPwdType;
use App\Form\CheckEmailType;
use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    
      
    
    
    
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
    public function checkEmail(Request $request)
    {

        // $user = new User;
        $form = $this->createform(CheckEmailType::class);

        //on nourri notre objet user avec nos données 
        $form->handleRequest($request);

        if($form->isSubmitted() and $form-> isValid()){

        $userEmail = $form['email']->getData();
        $existEmail = $this->getDoctrine()->getRepository(User::class)->findOneBy( array ('email' => $userEmail));
        dd($existEmail);
        }
           
       
        return $this->render('security/checkEmail.html.twig', ['form' => $form->createView()]);

      

        
    }
    
    /**
     * @Route("/forgotPwd", name="app_forgotPwd")
     * 
     */
    public function forgotPwd()
    {

        $user = new User;

        $form = $this->createform(ForgotPwdType::class, $user, []);

      

        if($form->isSubmitted() and $form-> isValid()){
            

        }

        return $this->render('security/forgotPwd.html.twig', ['form' => $form->createView()]);
    }
    
}
