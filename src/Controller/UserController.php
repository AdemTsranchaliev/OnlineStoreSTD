<?php


namespace App\Controller;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{



        /**
         * @Route("/infoForm/{id}", name="buy")
         * @param $id
         * @param $number
         */
        public
        function buy($id)
        {

                $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
                if ($product === null) {
                    return $this->render("commonFiles/404.html.twig");
                }
                $price = $product->getPrice();
                if (isset($_POST['firstName'])) {
                    $firstName = $_POST['firstName'];
                    $lastName = $_POST['lastName'];
                    $wayToDeliver = $_POST['wayToDelivery'];
                    $townAdress = $_POST['townAdress'];
                    $postalCode = $_POST['postCode'];
                    $adress = $_POST['adress'];
                    $townOfEkontOffice = $_POST['townOfEkontOffice'];
                    $ekontOffice = $_POST['ekontOffice'];
                    $phone = $_POST['phone'];
                    $email = $_POST['email'];
                    $comment = $_POST['comment'];
                    $modelNumber = $_POST['modelNumber'];
                    $modelColor = $_POST['modelColor'];
                    $modelTitle = $_POST['modelTitle'];
                    $modelPrice = $_POST['modelPrice'];
                    $Productize = $_POST['Productize'];
                    $order = new Orders();
                    $order->setName($firstName);
                    $order->setSurname($lastName);
                    if (strcmp($wayToDeliver, "Доставка с куриер до адрес") == 0) {
                        $order->setPopulatedPlace($townAdress);
                        $order->setPostalCode($postalCode);
                        $order->setAdress($adress);
                    } else if (strcmp($wayToDeliver, "Вземане лично от офис на куриер") == 0) {
                        $order->setPopulatedPlace($townOfEkontOffice);
                        $order->setEcontOffice($ekontOffice);
                    }
                    $order->setPhone($phone);
                    $order->setEmail($email);
                    $order->setAddedInfo($comment);
                    $order->setModelNumber($modelNumber);
                    $order->setModelId($id);
                    $order->setPriceOrdered($modelPrice);
                    $order->setModelNumberOrdered($Productize);
                    $order->setNewOrArchived(false);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($order);
                    $em->flush();
                    $model = $this->getDoctrine()->getRepository(Product::class)->find($id);
                    $count = $model->getBoughtCounter();
                    $count = intval($count) + 1;
                    $model->setBoughtCounter($count);
                    $emc = $this->getDoctrine()->getManager();
                    $emc->persist($model);
                    $emc->flush();
                    return $this->render('user/succesfulOrder.html.twig');
                }
                return $this->render('user/buyProduct.html.twig', ['price' => intval($price), 'product' => $product]);

        }

}
