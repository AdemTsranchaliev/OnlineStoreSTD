<?php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ShoppingCart;
use App\Entity\User;
use PhpParser\Node\Expr\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CommonController extends AbstractController
{

    /**
     * @Route("/error404", name="404")
     */
    public function error404()
    {


        if (isset($_COOKIE['_SC_KO'])) {
            $cookie = $_COOKIE['_SC_KO'];

            $shoppingCart = $this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId' => $cookie));


            if ($shoppingCart != null) {

                return $this->render('commonFiles/404.html.twig', ['productsCart' => $shoppingCart]);
            }

        }
        return $this->render('commonFiles/404.html.twig', ['productsCart' => null]);

    }

    /**
     * @Route("/successfulOrder", name="successfulOrder")
     */
    public function successfulOrder()
    {


        if (isset($_COOKIE['_SC_KO'])) {
            $cookie = $_COOKIE['_SC_KO'];

            $shoppingCart = $this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId' => $cookie));


            if ($shoppingCart != null) {

                return $this->render('user/succsesfulOrder.html.twig', ['productsCart' => $shoppingCart]);
            }

        }
        return $this->render('user/succsesfulOrder.html.twig', ['productsCart' => null]);

    }
    /**
     * @Route("/termsAndCotions", name="termsAndCotions")
     */
    public function termsAndCotions()
    {


        if (isset($_COOKIE['_SC_KO'])) {
            $cookie = $_COOKIE['_SC_KO'];

            $shoppingCart = $this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId' => $cookie));


            if ($shoppingCart != null) {

                return $this->render('commonFiles/termsAndConditions.html.twig', ['productsCart' => $shoppingCart]);
            }

        }
        return $this->render('commonFiles/termsAndConditions.html.twig', ['productsCart' => null]);

    }


}