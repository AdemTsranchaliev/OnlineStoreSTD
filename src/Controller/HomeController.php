<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
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
            while (count($bestSellers) != 6) {
                $max = 0;
                $shoes = new Product();
                foreach ($products as $pr) {
                    $num = $pr->getBoughtCounter();
                    if (intval($num) >= $max) {

                        $shoes = $pr;
                    }
                }
                array_push($bestSellers, $shoes);
                unset($products[array_search($shoes, $products)]);
            }
            $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
            while (count($lastOnes) != 6) {
                $max = 0;
                $shoes = new Product();
                foreach ($products as $pr) {
                    $num = $pr->getId();
                    if (intval($num) >= $max) {

                        $shoes = $pr;
                    }
                }
                array_push($lastOnes, $shoes);
                unset($products[array_search($shoes, $products)]);
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



        return $this->render('home/catalog.html.twig',['products'=>$category->getProduct()]);
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
            return $this->render('user/buyProduct.html.twig', ['product' => $product, 'size' => $s]);
        }
        $sizeAndNumber = $product->getSizes();
        $sizeAndNumber = explode(" ", $sizeAndNumber);
        $sizeAndNumber = array_filter(array_map('trim', $sizeAndNumber));
        $size = Array();
        $similar = Array();
        foreach ($sizeAndNumber as $item) {
            $test = explode('-', $item);
            if ($test[1] != 0) {
                array_push($size, $test[0]);
            }
        }

        return $this->render('home/singleProduct.html.twig', ['product' => $product, 'size' => $size]);
    }


}