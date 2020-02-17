<?php


namespace App\Controller;
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
        $securityContext = $this->container->get('security.authorization_checker');
        $user=new User();
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user= $this->getUser();
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


            /*
                                $wayToDeliver = $_POST['wayToDelivery'];

                                $modelNumber = $_POST['modelNumber'];
                                $modelColor = $_POST['modelColor'];
                                $modelTitle = $_POST['modelTitle'];
                                $modelPrice = $_POST['modelPrice'];
                                $Productize = $_POST['Productize'];

                                if (strcmp($wayToDeliver, "Доставка с куриер до адрес") == 0) {
                                    $order->setPopulatedPlace($townAdress);
                                    $order->setPostalCode($postalCode);
                                    $order->setAdress($adress);
                                } else if (strcmp($wayToDeliver, "Вземане лично от офис на куриер") == 0) {
                                    $order->setPopulatedPlace($townOfEkontOffice);
                                    $order->setEcontOffice($ekontOffice);
                                }
                       */


            $order->setNewOrArchived(false);
            $order->setConfirmed(false);
            $order->setUserId($user);




            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();



            return $this->render('user/succesfullOrder.html.twig');
        }


        return $this->render('user/buyProduct.html.twig', ['price' => intval($price), 'product' => $product,'user'=>$user]);

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

        return $this->render("user/myInformation.html.twig",['user'=>$user]);

    }
    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/myOrders", name="myOrders")
     */
    public function myOrders(Request $request)
    {
        $user = $this->getUser();


        return $this->render("user/myOrders.html.twig",['orders'=>$user->getOrders()]);

    }


}
