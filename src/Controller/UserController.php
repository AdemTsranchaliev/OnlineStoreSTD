<?php


namespace App\Controller;
use App\Entity\ShoppingCart;
use App\Entity\OrderProduct;
use App\Entity\Product;
use App\Entity\User;
use App\Form\Changeinfo;
use App\Form\Orders;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\DateTime;


class UserController extends AbstractController
{
    /**
     * @Route("/infoForm/{id}", name="buy")
     * @param $id
     * @param $number
     */
    public
    function buy($id,Request $request,\DateTimeInterface $date = null)
    {
        $user = $this->getUser();

        if ($user==null)
        {
            $user=new User();
        }

        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        if ($product === null) {
            return $this->render("commonFiles/404.html.twig");
        }
        $price = $product->getPrice();

        $order= new OrderProduct();
        $form = $this->createForm(Orders::class, $order);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $searchForProduct=$_POST['productId'];
            $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
            $product=new Product();
            foreach ($products as $value)
            {
                if ($searchForProduct == $value->getId())
                {
                    $product=$value;
                    break;
                }
            }
            if ($product->getId()==null)
            {
                return $this->render('commonFiles/404.html.twig');
            }
            $order->setProduct($product);



            $order->setNewOrArchived(false);
            $order->setConfirmed(false);

            if ($user->getName()!=null)
            {
                $order->setUserId($user);

            }




            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();

            if (isset($_COOKIE['_SC_KO']))
            {
                $cookie=$_COOKIE['_SC_KO'];

                $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));


                if ($shoppingCart!=null)
                {
                    return $this->render('user/succesfullOrder.html.twig',['productsCart'=>$shoppingCart]);

                }

            }

            return $this->render('user/succesfullOrder.html.twig',['productsCart'=>null]);
        }

        if (isset($_COOKIE['_SC_KO']))
        {
            $cookie=$_COOKIE['_SC_KO'];

            $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));


            if ($shoppingCart!=null)
            {
                return $this->render('user/buyProduct.html.twig', ['price' => intval($price), 'product' => $product,'user'=>$user,'productsCart'=>$shoppingCart]);

            }

        }
        return $this->render('user/buyProduct.html.twig', ['price' => intval($price), 'product' => $product,'user'=>$user,'productsCart'=>null]);

    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/myInformation", name="myInformation")
     */
    public function myInformation(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(Changeinfo::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }
        if (isset($_COOKIE['_SC_KO']))
        {
            $cookie=$_COOKIE['_SC_KO'];

            $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));


            if ($shoppingCart!=null)
            {
                return $this->render("user/myInformation.html.twig",['user'=>$user,'productsCart'=>$shoppingCart]);
            }

        }
        return $this->render("user/myInformation.html.twig",['user'=>$user,'productsCart'=>null]);

    }
    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/myOrders", name="myOrders")
     */
    public function myOrders(Request $request)
    {
        $user = $this->getUser();

        if (isset($_COOKIE['_SC_KO']))
        {
            $cookie=$_COOKIE['_SC_KO'];

            $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));


            if ($shoppingCart!=null)
            {
                return $this->render("user/myOrders.html.twig",['orders'=>$user->getOrders(),'productsCart'=>$shoppingCart]);
            }

        }
        return $this->render("user/myOrders.html.twig",['orders'=>$user->getOrders(),'productsCart'=>$shoppingCart]);

    }

    /**
     * @Route("/forgottenPassword", name="forgottenPassword")
     */
    public function forgottenPassword(Request $request)
    {


        if (isset($_COOKIE['_SC_KO']))
        {
            $cookie=$_COOKIE['_SC_KO'];

            $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));


            if ($shoppingCart!=null)
            {

                return $this->render("user/forgottenPassword.html.twig",['productsCart'=>$shoppingCart]);
            }

        }
        return $this->render("user/forgottenPassword.html.twig",['productsCart'=>null]);


    }
    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/seeOrder/{id}", name="seeOrder")
     * @param $id
     */
    public function seeOrder(Request $request, $id)
    {

        $order = $this->getDoctrine()->getRepository(OrderProduct::class)->find($id);

        $user = $this->getUser();
        if ($order==null)
        {
            return $this->render('commonFiles/404.html.twig');
        }
        if ($order->getUserId()==null)
        {
            return $this->render('commonFiles/404.html.twig');
        }
        if ($order->getUserId()->getId()!=$user->getId())
        {
            return $this->redirect('myOrders');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            if($order->getConfirmed()==0)
            {
                $order->setConfirmed(true);
            }
            else
            {
                $order->setNewOrArchived(true);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();

        }
        if (isset($_COOKIE['_SC_KO']))
        {
            $cookie=$_COOKIE['_SC_KO'];

            $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));


            if ($shoppingCart!=null)
            {
                return $this->render("admin/seeOrder.html.twig",['productsCart'=>$shoppingCart,'order' => $order]);
            }

        }
        return $this->render("admin/seeOrder.html.twig",['productsCart'=>null,'order' => $order]);

    }


}
