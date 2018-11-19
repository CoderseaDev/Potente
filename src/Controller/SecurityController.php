<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\User\UserChecker;



class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
//           $userChecker = new UserChecker();
         //get the login error if there is one
           $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
          $Username = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'username' => $Username,
            'error'    => $error,
        ));
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logout() {}

}
