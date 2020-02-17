<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ShoppingCart;
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
                                <div class=\"product__actions\">
                                    <a href=\"quickview.html\" class=\"product__quickview\">
                                        <i class=\"ui-eye\"></i>
                                        <span>Quick View</span>
                                    </a>
                                    <a href=\"#\" class=\"product__add-to-wishlist\">
                                        <i class=\"ui-heart\"></i>
                                        <span>Wishlist</span>
                                    </a>
                                </div>
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

            $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findOneBy(array('coocieId'=>$cookie));

            $id=$_POST['id'];
            $size=$_POST['sizeProduct'];
            $product=$this->getDoctrine()->getRepository(Product::class)->find($id);


            if($shoppingCart==null)
            {
                $shoppingCart=new ShoppingCart();

                $shoppingCart->addCartProduct($product);
                $shoppingCart->setCoocieId($cookie);
                $shoppingCart->setModelSize($product->getId().'?'.$size.'-1');
                $shoppingCart->setPrice(0);

            }
            else {
                $products = $shoppingCart->getCartProduct();

                $flag = false;
                for ($i = 0; $i < count($products); $i++) {
                    if (intval($id) == $products[$i]->getId()) {
                        $product = $products[$i];
                        $flag = true;
                        break;
                    }
                }

              if ($flag) {
                    $modelSize=$shoppingCart->getModelSize();
                  $separatedStr = explode('|',$modelSize );

                  for ($j=0;$j<count($separatedStr);$j++)
                  {
                      $idSizes= explode('?', $separatedStr[$j]);
                      $text='';
                      if(strcmp($id,$idSizes[0])==0)
                      {
                          $sizeQuantity= explode(',', $idSizes[1]);
                          $text2=$id.'?';
                          $isFound=false;
                          for ($i=0;$i<count($sizeQuantity);$i++)
                          {
                              $szQu=explode('-', $sizeQuantity[$i]);
                              $isFound=true;
                              if(strcmp($szQu[0],$size)==0)
                              {

                                  $quantity=intval($szQu[1])+1;
                                  if ($i==0)
                                  {
                                      $text2.=$szQu[0].'-'.$quantity;
                                  }
                                  else
                                  {
                                      $text2.=','.$szQu[0].'-'.$quantity;
                                  }
                                  $isFound=true;
                              }
                              else
                              {
                                  if ($i==0)
                                  {
                                      $text2.=$sizeQuantity[$i];
                                  }
                                  else
                                  {
                                      $text2.=','.$sizeQuantity[$i];
                                  }
                              }
                          }
                          if ($isFound)
                          {
                              $text2.=','.$size.'-1';
                          }
                          if($j==0)
                          {
                              $text.=$text2;
                          }
                          else
                          {
                              $text.='|'.$text2;
                          }
                      }
                      else
                      {
                          if($j==0)
                          {
                              $text.=$separatedStr[$j];
                          }
                          else
                          {
                              $text.='|'.$separatedStr[$j];
                          }
                      }
                  }

                  $shoppingCart->setModelSize($text);
              }
              else
              {
                  $text=$shoppingCart->getModelSize();
                  $text.='|'.$id.'?'.$size.'-1';
                  $shoppingCart->setModelSize($text);
              }

            }


            $em = $this->getDoctrine()->getManager();
            $em->persist($shoppingCart);
            $em->flush();
            return new Response($id);
        }

    }
}