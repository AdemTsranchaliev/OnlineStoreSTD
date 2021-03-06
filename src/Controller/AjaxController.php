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

            $arr2=[];
            $categories = $this->getDoctrine()
                ->getRepository(Category::class)
                ->findAll();

            foreach ($categories as $prd) {

                if (strcmp($category, $prd->getName()) == 0) {
                    $arr2 = $prd->getProduct();
                    break;
                }
            }
            $arr=[];
            for ($i=0;$i<count($arr2);$i++)
            {
                if ($arr2[$i]->getIsDetelet()==0)
                {
                    array_push($arr,$arr2[$i]) ;
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
            $size=null;
            if (isset($_POST['sizeProduct']))
            {
                $size=$_POST['sizeProduct'];
            }
            $color=null;
            if (isset($_POST['colorProduct']))
            {
                $color=$_POST['colorProduct'];
            }
            $product=$this->getDoctrine()->getRepository(Product::class)->find($id);


            $response='';


            if($shoppingCart==null)
            {
                $shoppingCart=new ShoppingCart();

                $shoppingCart->addCartProduct($product);
                $shoppingCart->setProductId($product->getId());
                $shoppingCart->setColor($color);
                $shoppingCart->setCoocieId($cookie);
                $shoppingCart->setModelSize($size);
                $shoppingCart->setPrice($product->getPrice());
                $shoppingCart->setQuantity(1);


            }
            else {
                $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findOneBy(array('coocieId'=>$cookie,'modelSize'=>$size,'color'=>$color,'productId'=>$product->getId()));
                if ($shoppingCart==null)
                {
                    $shoppingCart=new ShoppingCart();

                    $shoppingCart->addCartProduct($product);
                    $shoppingCart->setCoocieId($cookie);
                    $shoppingCart->setColor($color);
                    $shoppingCart->setModelSize($size);
                    $shoppingCart->setPrice($product->getPrice());
                    $shoppingCart->setQuantity(1);
                    $shoppingCart->setProductId($product->getId());
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($shoppingCart);
                    $em->flush();
                    $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findOneBy(array('coocieId'=>$cookie,'modelSize'=>$size,'productId'=>$product->getId()));

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


            $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));


            $response.="<div class=\"top-bar__item nav-cart\" id=\"cartIdnt\">                
                <a href=\"/seeShoppingCart\">
                  <i class=\"ui-bag\"></i>(<span id=\"cartProductCount\">".count($shoppingCart)."</span>)
                </a>
                <div class=\"nav-cart__dropdown\">
                  <div class=\"nav-cart__items\" style=\"overflow : scroll; scrollbar-width: thin; height: 370px\">";


                 $total=0;
                 foreach ($shoppingCart as $product2)
                 {
                     $total+=$product2->getPrice();
                     $response.="<div class=\"nav-cart__item clearfix\">
                      <div class=\"nav-cart__img\">
                        <a href=\"#\">
                          <img src=\"/img/uploads/".$product2->getCartProduct()[0]->getId().".0.jpg\" height=\"100\" width=\"60\" alt=\"\">
                        </a>
                      </div>
                      <div class=\"nav-cart__title\">
                        <a href=\"#\">
                          ".$product2->getCartProduct()[0]->getTitle()."
                        </a>
                        <div class=\"nav-cart__price\">
                          <span>".$product2->getQuantity()." x</span>
                          <span>  ".number_format($product2->getCartProduct()[0]->getPrice(),2)."лв.</span>
                        </div>
                         <div class=\"nav-cart__price\">
                             <span>Размер: </span>
                             <span>".$product2->getModelSize()."</span>
                         </div>
                         <div class=\"nav-cart__price\">
                             <span>Общо: </span>
                             <span>".$product2->getPrice()." лв.</span>
                         </div>
                      </div>
                      <div class=\"nav-cart__remove\">
                        <a href=\"#\" onclick=\"deleteProd(".$product2->getCartProduct()[0]->getId().")\"><i class=\"ui-close\"></i></a>
                      </div>
                    </div>
";
                 }




            $response.="   </div> <!-- end cart items -->

                  <div class=\"nav-cart__summary\">
                    <span>Общо за количка: </span>
                    <span class=\"nav-cart__total-price\">".number_format($total,2)."лв</span>
                  </div>

                  <div class=\"nav-cart__actions mt-20\">
                      <a href=\"/seeShoppingCart\" class=\"btn btn-md btn-light\"><span>Виж количката</span></a>
                      <a href=\"/ordering\" class=\"btn btn-md btn-color mt-10\"><span>Към поръчка</span></a>
                  </div>
                </div>
              </div>
            </div>";



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


            if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message'])) {


                $name = $_POST['name'];
                $email = $_POST['email'];
                $subject = $_POST['subject'];
                $messagee = $_POST['message'];


                $to = "ademcran4aliew@gmail.com";


                $message = "
              <html>
              <head>
              <title>Email от контактна форма</title>
              </head>
              <body>
              <h1>Съобщение</h1>
              <p>" . $messagee . "</p>
              <table>
              <tr>
              <th>Име</th>
              <th>Email</th>
              </tr>
              <tr>
              <td>" . $name . "</td>
              <td>" . $email . "</td>
              
              </tr>
              </table>
              </body>
              </html>
              ";

// Always set content-type when sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";



                mail($to, $subject, $message, $headers);


                return new Response("yes");

            }
        }
        return new Response("no");

    }

    /**
     * @Route("/deleteProd")
     */
    public function deleteProd(Request $request)
    {


        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

            $id=$_POST['id'];


            $cookie=$_COOKIE['_SC_KO'];

            $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findOneBy(array('coocieId'=>$cookie,'productId'=>$id));

            if ($shoppingCart!=null)
            {
                $entityManager=$this->getDoctrine()->getManager();
                $entityManager->remove($shoppingCart);
                $entityManager->flush();

                $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));
                $response='';

                $response.="<div class=\"top-bar__item nav-cart\" id=\"cartIdnt\">                
                <a href=\"/seeShoppingCart\">
                  <i class=\"ui-bag\"></i>(".count($shoppingCart).")
                </a>
                <div class=\"nav-cart__dropdown\">
                  <div class=\"nav-cart__items\" style=\"overflow : scroll; scrollbar-width: thin; height: 370px\">";


                $total=0;
                foreach ($shoppingCart as $product2)
                {
                    $total+=$product2->getPrice();
                    $response.="<div class=\"nav-cart__item clearfix\">
                      <div class=\"nav-cart__img\">
                        <a href=\"#\">
                          <img src=\"/img/uploads/".$product2->getCartProduct()[0]->getId().".0.jpg\" height=\"100\" width=\"60\" alt=\"\">
                        </a>
                      </div>
                      <div class=\"nav-cart__title\">
                        <a href=\"#\">
                          ".$product2->getCartProduct()[0]->getTitle()."
                        </a>
                        <div class=\"nav-cart__price\">
                          <span>".$product2->getQuantity()." x</span>
                          <span>  ".number_format($product2->getCartProduct()[0]->getPrice(),2)."лв.</span>
                        </div>
                         <div class=\"nav-cart__price\">
                             <span>Размер: </span>
                             <span>".$product2->getModelSize()."</span>
                         </div>
                         <div class=\"nav-cart__price\">
                             <span>Общо: </span>
                             <span>".$product2->getPrice()." лв.</span>
                         </div>
                      </div>
                      <div class=\"nav-cart__remove\">
                        <a href=\"#\" onclick=\"deleteProd(".$product2->getCartProduct()[0]->getId().")\"><i class=\"ui-close\"></i></a>
                      </div>
                    </div>
";
                }




                $response.="   </div> <!-- end cart items -->

                  <div class=\"nav-cart__summary\">
                    <span>Общо за количка: </span>
                    <span class=\"nav-cart__total-price\">".number_format($total,2)."лв</span>
                  </div>

                  <div class=\"nav-cart__actions mt-20\">
                      <a href=\"/seeShoppingCart\" class=\"btn btn-md btn-light\"><span>Виж количката</span></a>
                      <a href=\"/ordering\" class=\"btn btn-md btn-color mt-10\"><span>Към поръчка</span></a>
                  </div>
                </div>
              </div>
            </div>";







                return new Response($response);
            }


            return new Response("yes");
        }
    }


}