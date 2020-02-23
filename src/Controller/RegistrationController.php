<?php

namespace App\Controller;
use App\Entity\ShoppingCart;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(array('ROLE_USER'));
            $user->setName($form->get('name')->getData());

            $user->setSurname($form->get('surname')->getData());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }
        if (isset($_COOKIE['_SC_KO']))
        {
            $cookie=$_COOKIE['_SC_KO'];

            $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));


            if ($shoppingCart!=null)
            {
                return $this->render('registration/register.html.twig', [
                    'registrationForm' => $form->createView(),'productsCart'=>$shoppingCart
                ]);

            }

        }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),'productsCart'=>null
        ]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/changePassword", name="changePassword")
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator)
    {
        $user = $this->getUser();


        if(isset($_POST['oldPassword']))
        {
            $tempUser=new User();


            $encoded = $passwordEncoder->encodePassword($tempUser, $_POST['oldPassword']);
            $tempUser->setPassword($encoded);

            $match = $passwordEncoder->isPasswordValid($user, $tempUser->getPassword());
            echo var_dump($user);
            echo var_dump($match);
            echo var_dump( $_POST['oldPassword']);
            echo var_dump($encoded);
        }



        return $this->render("user/changePassword.html.twig");

    }
}
