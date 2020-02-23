<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ShoppingCart;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {

            $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
            $bestSellers = Array();
            $lastOnes = Array();

             for ($i = 0; $i < count($products); $i++)
             {
                 for ($j = 0; $j < count($products) - $i - 1; $j++)
                 {
                     if( $products[$j]->getBoughtCounter() < $products[$j+1]->getBoughtCounter() )
                     {
                         $temp = $products[$j];
                         $products[$j]=$products[$j+1];
                         $products[$j+1]=$temp;
                     }
                 }
             }

        for ($i = 0; $i < 6; $i++)
        {
            array_push($bestSellers, $products[$i]);
        }

        for ($i = 0; $i < count($products); $i++)
        {
            for ($j = 0; $j < count($products) - $i - 1; $j++)
            {
                if( $products[$j]->getId() < $products[$j+1]->getId() )
                {
                    $temp = $products[$j];
                    $products[$j]=$products[$j+1];
                    $products[$j+1]=$temp;
                }
            }
        }
        for ($i = 0; $i < 6; $i++)
        {
            array_push($lastOnes, $products[$i]);
        }

        if (isset($_COOKIE['_SC_KO']))
        {
            $cookie=$_COOKIE['_SC_KO'];

            $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));


            if ($shoppingCart!=null)
            {

                return $this->render('home/index.html.twig', ['lastOnes' => $lastOnes, 'bestSellers' => $bestSellers,'productsCart'=>$shoppingCart]);
            }

        }
        return $this->render('home/index.html.twig', ['lastOnes' => $lastOnes, 'bestSellers' => $bestSellers,'productsCart'=>null]);

    }

    /**
     * @param $categoryName
     *
     * @Route("/catalog/{categoryName}", name="catalog")
     */
    public function catalog($categoryName)
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $category=new Category();
        foreach ($categories as $value)
        {
            if (strcmp($categoryName, $value->getTag()) == 0)
            {
                $category=$value;
                break;
            }
        }
        if ($category->getId()==null)
        {
            return $this->render('commonFiles/404.html.twig');
        }


        if (isset($_COOKIE['_SC_KO']))
        {
            $cookie=$_COOKIE['_SC_KO'];

            $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));


            if ($shoppingCart!=null)
            {
                return $this->render('home/catalog.html.twig',['products'=>$category->getProduct(),'category'=>$category->getName(),'allCategories'=>$categories,'productsCart'=>$shoppingCart]);
            }

        }
        return $this->render('home/catalog.html.twig',['products'=>$category->getProduct(),'category'=>$category->getName(),'allCategories'=>$categories,'productsCart'=>null]);
    }

    /**
     * @Route("/singleProduct/{id}", name="singleProduct")
     * @param $id
     */
    public function singleProduct($id)
    {

        if (!isset($_COOKIE['_SC_KO']))
        {
            setcookie('_SC_KO',bin2hex(random_bytes(10)),time() + (86400 * 30),'/');

        }
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        if ($product === null) {
            return $this->render('commonFiles/404.html.twig');
        }
        if (isset($_POST['size__select'])) {

            $s = $_POST['size__select'];
            $user= $this->getUser();
            if ($user==null)
            {
                $user=new User();
            }
            if (isset($_COOKIE['_SC_KO']))
            {
                $cookie=$_COOKIE['_SC_KO'];

                $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));


                if ($shoppingCart!=null)
                {
                    return $this->render('user/buyProduct.html.twig', ['product' => $product, 'size' => $s,'user'=>$user,'productsCart'=>$shoppingCart]);
                }

            }
            return $this->render('user/buyProduct.html.twig', ['product' => $product, 'size' => $s,'user'=>$user,'productsCart'=>null]);
        }
        $sizeAndNumber = $product->getSizes();
        $sizeAndNumber = explode(" ", $sizeAndNumber);
        $sizeAndNumber = array_filter(array_map('trim', $sizeAndNumber));
        $size = Array();

        foreach ($sizeAndNumber as $item) {
            $test = explode('-', $item);
            if ($test[1] != 0) {
                array_push($size, $test[0]);
            }
        }
        $similar = $this->getDoctrine()->getRepository(Category::class)->findOneBy(array('tag'=>$product->getCategoryId()->getTag()));

        if (isset($_COOKIE['_SC_KO']))
        {
            $cookie=$_COOKIE['_SC_KO'];

            $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));


            if ($shoppingCart!=null)
            {
                return $this->render('home/singleProduct.html.twig', ['product' => $product, 'size' => $size,'similar'=>$similar->getProduct(),'productsCart'=>$shoppingCart]);
            }

        }
        return $this->render('home/singleProduct.html.twig', ['product' => $product, 'size' => $size,'similar'=>$similar->getProduct(),'productsCart'=>null]);
    }
    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {


        if (isset($_COOKIE['_SC_KO']))
        {
            $cookie=$_COOKIE['_SC_KO'];

            $shoppingCart=$this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId'=>$cookie));


            if ($shoppingCart!=null)
            {
                return $this->render('commonFiles/contact.html.twig',['productsCart'=>$shoppingCart]);
            }

        }





        return $this->render('commonFiles/contact.html.twig',['productsCart'=>null]);
    }

}