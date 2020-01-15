<?php


namespace App\Controller;
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
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/catalog", name="catalog")
     */
    public function catalog()
    {

        return $this->render('home/index.html.twig');
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

        return $this->render('home/singleProduct.html.twig', ['product' => $product]);
    }


}