<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;

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
            return $this->render('home/index.html.twig', ['lastOnes' => $lastOnes, 'bestSellers' => $bestSellers]);

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



        return $this->render('home/catalog.html.twig',['products'=>$category->getProduct(),'category'=>$category->getName()]);
    }

    /**
     * @Route("/singleProduct/{id}", name="singleProduct")
     * @param $id
     */
    public function singleProduct($id)
    {


        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        if ($product === null) {
            return $this->render('commonFiles/404.html.twig');
        }
        if (isset($_POST['size__select'])) {

            $s = $_POST['size__select'];
            $securityContext = $this->container->get('security.authorization_checker');


            $user= $this->getUser();
            if ($user==null)
            {
                $user=new User();
            }
            return $this->render('user/buyProduct.html.twig', ['product' => $product, 'size' => $s,'user'=>$user]);
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

        return $this->render('home/singleProduct.html.twig', ['product' => $product, 'size' => $size,'similar'=>$similar->getProduct()]);
    }

}