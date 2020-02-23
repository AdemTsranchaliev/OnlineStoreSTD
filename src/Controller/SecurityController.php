<?php

namespace App\Controller;
use App\Entity\ShoppingCart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             if (isset($_COOKIE['_SC_KO']))
             {
                 $cookie=$_COOKIE['_SC_KO'];

                 $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));


                 if ($shoppingCart!=null)
                 {
                     return $this->redirectToRoute('index',['productsCart'=>$shoppingCart]);
                 }

             }
             return $this->redirectToRoute('index',['productsCart'=>null]);
        }


        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        if (isset($_COOKIE['_SC_KO']))
        {
            $cookie=$_COOKIE['_SC_KO'];

            $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));


            if ($shoppingCart!=null)
            {
                return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error,'productsCart'=>$shoppingCart]);
            }

        }
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error,'productsCart'=>null]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}
