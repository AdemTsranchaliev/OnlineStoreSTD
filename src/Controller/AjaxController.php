<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ShoppingCart;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Response;


class AjaxController extends AbstractController
{

    /**
     * @Route("/ajaxSort")
     */
    public function ajaxAction(Request $request) {


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

            $command=$_POST['command'];
            $category=$_POST['categoryName'];

            $arr=[];
            $categories = $this->getDoctrine()
                ->getRepository(Category::class)
                ->findAll();

            foreach ($categories as $prd) {

                if (strcmp($category, $prd->getName()) == 0) {
                    $arr = $prd->getProduct();
                    break;
                }
            }
            if(strcmp($command,"price-low-to-high")==0)
            {
                for ($i = 0; $i < count($arr); $i++) {

                    for ($j = 0; $j < count($arr) - $i - 1; $j++)
                    {
                        if( $arr[$j]->getPrice() < $arr[$j+1]->getPrice() )
                        {
                            $temp = $arr[$j];
                            $arr[$j]=$arr[$j+1];
                            $arr[$j+1]=$temp;

                        }

                    }

                }
            }
            if(strcmp($command,"price-high-to-low")==0)
            {
                for ($i = 0; $i < count($arr); $i++) {

                    for ($j = 0; $j < count($arr) - $i - 1; $j++)
                    {
                        if( $arr[$j]->getPrice() > $arr[$j+1]->getPrice() )
                        {
                            $temp = $arr[$j];
                            $arr[$j]=$arr[$j+1];
                            $arr[$j+1]=$temp;

                        }

                    }

                }
            }
            if(strcmp($command,"by-popularity")==0)
            {
                for ($i = 0; $i < count($arr); $i++) {

                    for ($j = 0; $j < count($arr) - $i - 1; $j++)
                    {
                        if( $arr[$j]->getBoughtCounter() < $arr[$j+1]->getBoughtCounter() )
                        {
                            $temp = $arr[$j];
                            $arr[$j]=$arr[$j+1];
                            $arr[$j+1]=$temp;

                        }

                    }

                }
            }
            if(strcmp($command,"date")==0)
            {
                for ($i = 0; $i < count($arr); $i++) {

                    for ($j = 0; $j < count($arr) - $i - 1; $j++)
                    {
                        if( $arr[$j]->getId() < $arr[$j+1]->getId() )
                        {
                            $temp = $arr[$j];
                            $arr[$j]=$arr[$j+1];
                            $arr[$j+1]=$temp;

                        }

                    }

                }
            }


            $ready='';

            $ready.=" <div class=\"row row-8\" id=\"divSort\">";

            $i=1;
            foreach ($arr as $prd) {

                $ready.="   <div class=\"col-md-4 col-sm-6 product\" >
                            <div class=\"product__img-holder\">
                                <a href=\"\singleProduct\\".$prd->getId()."\" class=\"product__link\">
                                    <img src=\"/img/uploads/".$prd->getId().".0.jpg\" alt=\"\" class=\"product__img\" id=\"img_1B\" height=\"300px\"> ";
                if ($prd->getPhotoCount()>1)
                {
                    $ready.="  <img src=\"/img/uploads/".$prd->getId().".1.jpg\" alt=\"\" class=\"product__img-back\" id=\"img_1S\" height=\"300px\">";
                }
                $ready.="    </a>
                                
                            </div>

                            <div class=\"product__details\">
                                <h3 class=\"product__title\">
                                    <a href=\"\singleProduct\\".$prd->getId()."\">".$prd->getTitle()."</a>
                                </h3>
                            </div>

                            <span class=\"product__price\">
                  <ins>
                    <span class=\"amount\">".(number_format($prd->getPrice(), 2))."лв.</span>
                  </ins>
                </span>
                        </div> <!-- end product -->";

                $i++;

            }
            $ready.=" </div>";
            file_put_contents('C:\Users\Asus\Desktop\untitled1\text.txt', $ready);
            return new Response($ready);
        } else {
            return $this->render('student/ajax.html.twig');
        }



    }


    /**
     * @Route("/ajaxAddToCart")
     */
    public function ajaxAddToCart(Request $request) {


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            if (!isset($_COOKIE['_SC_KO']))
            {
                setcookie('_SC_KO',bin2hex(random_bytes(10)),time() + (86400 * 30));

            }
            $cookie=$_COOKIE['_SC_KO'];

            $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));

            $id=$_POST['id'];
            $size=$_POST['sizeProduct'];
            $product=$this->getDoctrine()->getRepository(Product::class)->find($id);


            $response='';

            if($shoppingCart==null)
            {
                $shoppingCart=new ShoppingCart();

                $shoppingCart->addCartProduct($product);
                $shoppingCart->setProductId($product->getId());

                $shoppingCart->setCoocieId($cookie);
                $shoppingCart->setModelSize($size);
                $shoppingCart->setPrice($product->getPrice());
                $shoppingCart->setQuantity(1);
                $response.="   <div class=\"nav-cart__dropdown \">
                                    <div class=\"nav-cart__items .overflow-auto\" style=\"overflow : scroll; height: 370px\">
                                                <div class=\"nav-cart__item clearfix\">
                                                    <div class=\"nav-cart__img\">
                                                        <a href=\"#\">
                                                            <img src=\"/img/uploads/".$product->getId().".0.jpg\" height=\"100\" width=\"60\">
                                                        </a>
                                                    </div>
                                                    <div class=\"nav-cart__title\">
                                                        <a href=\"#\">
                                                           ".$product->getTitle()."
                                                        </a>
                                                        <div class=\"nav-cart__price\">
                                                            <span>1 x</span>
                                                            <span>".$product->getPrice()." лв</span>
                                                        </div>
                                                        <div class=\"nav-cart__price\">
                                                            <span>Размер: </span>
                                                            <span>".$size."</span>
                                                        </div>
                                                        <div class=\"nav-cart__price\">
                                                            <span>Общо: </span>
                                                            <span>".$product->getPrice()." лв</span>
                                                        </div>
                                                    </div>
                                                    <div class=\"nav-cart__remove\">
                                                        <a href=\"#\"><i class=\"ui-close\"></i></a>
                                                    </div>
                                                </div>
                                                  <div id='insertCartProductHere'>

                                    </div>
                                    </div> <!-- end cart items -->
                                    
                                 

                                    <div class=\"nav-cart__summary\">
                                        <span>Общо: </span>
                                        <span class=\"nav-cart__total-price\" id='totalPrice'>".$product->getPrice()." лв </span>
                                    </div>

                                    <div class=\"nav-cart__actions mt-20\">
                                        <a href=\"shop-cart.html\" class=\"btn btn-md btn-light\"><span>View Cart</span></a>
                                        <a href=\"shop-checkout.html\" class=\"btn btn-md btn-color mt-10\"><span>Proceed to Checkout</span></a>
                                    </div>
                                </div>";

            }
            else {
                $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findOneBy(array('coocieId'=>$cookie,'modelSize'=>$size,'productId'=>$product->getId()));
                if ($shoppingCart==null)
                {
                    $shoppingCart=new ShoppingCart();

                    $shoppingCart->addCartProduct($product);
                    $shoppingCart->setCoocieId($cookie);
                    $shoppingCart->setModelSize($size);
                    $shoppingCart->setPrice($product->getPrice());
                    $shoppingCart->setQuantity(1);
                    $shoppingCart->setProductId($product->getId());
                    $response.="   
                                                <div class=\"nav-cart__item clearfix\">
                                                    <div class=\"nav-cart__img\">
                                                        <a href=\"#\">
                                                            <img src=\"/img/uploads/".$product->getId().".0.jpg\" height=\"100\" width=\"60\">
                                                        </a>
                                                    </div>
                                                    <div class=\"nav-cart__title\">
                                                        <a href=\"#\">
                                                           ".$product->getTitle()."
                                                        </a>
                                                        <div class=\"nav-cart__price\">
                                                            <span>1 x</span>
                                                            <span>".$product->getPrice()." лв</span>
                                                        </div>
                                                        <div class=\"nav-cart__price\">
                                                            <span>Размер: </span>
                                                            <span>".$size."</span>
                                                        </div>
                                                        <div class=\"nav-cart__price\">
                                                            <span>Общо: </span>
                                                            <span>".$product->getPrice()." лв</span>
                                                        </div>
                                                    </div>
                                                    <div class=\"nav-cart__remove\">
                                                        <a href=\"#\"><i class=\"ui-close\"></i></a>
                                                    </div>
                                                </div>";
                }
                else
                {
                    $shoppingCart->setPrice($product->getPrice()*($shoppingCart->getQuantity()+1));
                    $shoppingCart->setQuantity($shoppingCart->getQuantity()+1);
                }

            }


            $em = $this->getDoctrine()->getManager();
            $em->persist($shoppingCart);
            $em->flush();
            return new Response($response);
        }

    }

    /**
     * @Route("/checkIfMailExists")
     */
    public function checkIfMailExists(Request $request)
    {


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

            $email=$_POST['email'];
            $emailExist=$this->getDoctrine()->getRepository(User::class)->findOneBy(array('email'=>$email));

            if ($emailExist==null)
            {
                return new Response("no");
            }
            else
            {
                return new Response("yes");
            }
        }
    }

    /**
     * @Route("/sendMail")
     */
    public function sendMail(Request $request)
    {


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

            $name=$_POST['name'];
            $email=$_POST['email'];
            $subject=$_POST['subject'];
            $message=$_POST['message'];






                return new Response("yes");

        }
    }


}