<?php


namespace App\Controller;
use App\Entity\OrderProduct;
use App\Entity\Product;
use App\Form\Orders;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends AbstractController
{
    /**
     * @Route("/infoForm/{id}", name="buy")
     * @param $id
     * @param $number
     */
    public
    function buy($id,Request $request)
    {

        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        if ($product === null) {
            return $this->render("commonFiles/404.html.twig");
        }
        $price = $product->getPrice();

        $order= new OrderProduct();
        $form = $this->createForm(Orders::class, $order);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            echo var_dump($_SESSION['name']);
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

            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();



            return $this->render('user/succesfullOrder.html.twig');
        }
        return $this->render('user/buyProduct.html.twig', ['price' => intval($price), 'product' => $product]);

    }

}
