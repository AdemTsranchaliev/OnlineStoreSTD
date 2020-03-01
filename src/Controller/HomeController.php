<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ShoppingCart;
use App\Entity\User;
use PhpParser\Node\Expr\Array_;
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

        for ($i = 0; $i < count($products); $i++) {
            for ($j = 0; $j < count($products) - $i - 1; $j++) {
                if ($products[$j]->getBoughtCounter() < $products[$j + 1]->getBoughtCounter()) {
                    $temp = $products[$j];
                    $products[$j] = $products[$j + 1];
                    $products[$j + 1] = $temp;
                }
            }
        }

        for ($i = 0; $i < 6; $i++) {
            array_push($bestSellers, $products[$i]);
        }

        for ($i = 0; $i < count($products); $i++) {
            for ($j = 0; $j < count($products) - $i - 1; $j++) {
                if ($products[$j]->getId() < $products[$j + 1]->getId()) {
                    $temp = $products[$j];
                    $products[$j] = $products[$j + 1];
                    $products[$j + 1] = $temp;
                }
            }
        }
        for ($i = 0; $i < 6; $i++) {
            array_push($lastOnes, $products[$i]);
        }

        if (isset($_COOKIE['_SC_KO'])) {
            $cookie = $_COOKIE['_SC_KO'];

            $shoppingCart = $this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId' => $cookie));


            if ($shoppingCart != null) {

                return $this->render('home/index.html.twig', ['lastOnes' => $lastOnes, 'bestSellers' => $bestSellers, 'productsCart' => $shoppingCart]);
            }

        }
        return $this->render('home/index.html.twig', ['lastOnes' => $lastOnes, 'bestSellers' => $bestSellers, 'productsCart' => null]);

    }

    /**
     * @param $categoryName
     *
     * @Route("/catalog/{categoryName}", name="catalog")
     */
    public function catalog($categoryName)
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $category = new Category();
        foreach ($categories as $value) {
            if (strcmp($categoryName, $value->getTag()) == 0) {
                $category = $value;
                break;
            }
        }
        if ($category->getId() == null) {
            return $this->render('commonFiles/404.html.twig');
        }


        if (isset($_COOKIE['_SC_KO'])) {
            $cookie = $_COOKIE['_SC_KO'];

            $shoppingCart = $this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId' => $cookie));


            if ($shoppingCart != null) {
                return $this->render('home/catalog.html.twig', ['products' => $category->getProduct(), 'category' => $category->getName(), 'allCategories' => $categories, 'productsCart' => $shoppingCart]);
            }

        }
        return $this->render('home/catalog.html.twig', ['products' => $category->getProduct(), 'category' => $category->getName(), 'allCategories' => $categories, 'productsCart' => null]);
    }

    /**
     * @Route("/singleProduct/{id}", name="singleProduct")
     * @param $id
     */
    public function singleProduct($id)
    {

        if (!isset($_COOKIE['_SC_KO'])) {
            setcookie('_SC_KO', bin2hex(random_bytes(10)), time() + (86400 * 30), '/');

        }
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        if ($product === null) {
            return $this->render('commonFiles/404.html.twig');
        }
        if (isset($_POST['size__select'])) {

            $s = $_POST['size__select'];
            $user = $this->getUser();
            if ($user == null) {
                $user = new User();
            }
            if (isset($_COOKIE['_SC_KO'])) {
                $cookie = $_COOKIE['_SC_KO'];

                $shoppingCart = $this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId' => $cookie));


                if ($shoppingCart != null) {
                    return $this->render('user/buyProduct.html.twig', ['product' => $product, 'size' => $s, 'user' => $user, 'productsCart' => $shoppingCart]);
                }

            }
            return $this->render('user/buyProduct.html.twig', ['product' => $product, 'size' => $s, 'user' => $user, 'productsCart' => null]);
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
        $allProducts = $this->getDoctrine()->getRepository(Category::class)->findOneBy(array('tag' => $product->getCategoryId()->getTag()));
        $similar = Array();
        $similarIndexes = Array();
        foreach ($allProducts->getProduct() as $prd) {
            if ($prd->getId() != intval($id)) {
                array_push($similarIndexes, strval($prd->getId()));
                array_push($similar, $prd);
            }

            if (count($similar) == 6) {
                break;
            }
        }
        if (count($similar) < 6) {
            while (count($similar) != 6) {

                $rand = rand(2, 8);

                if ($rand == $product->getCategoryId()->getId()) {
                    $rand = rand(2, 8);
                    while ($rand == $product->getCategoryId()->getId()) {
                        $rand = rand(2, 8);
                    }
                }
                $allProducts2 = $this->getDoctrine()->getRepository(Category::class)->find($rand);


                foreach ($allProducts2->getProduct() as $item) {
                    if (count($similar) == 6) {
                        break;
                    } else {
                        if (!in_array(strval($item->getId()), $similarIndexes)) {
                            array_push($similar, $item);
                            array_push($similarIndexes, $item->getId());
                        }

                    }


                }
            }
        }


        if (isset($_COOKIE['_SC_KO'])) {
            $cookie = $_COOKIE['_SC_KO'];

            $shoppingCart = $this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId' => $cookie));


            if ($shoppingCart != null) {
                return $this->render('home/singleProduct.html.twig', ['product' => $product, 'size' => $size, 'similar' => $similar, 'productsCart' => $shoppingCart]);
            }

        }
        return $this->render('home/singleProduct.html.twig', ['product' => $product, 'size' => $size, 'similar' => $similar, 'productsCart' => null]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {


        if (isset($_COOKIE['_SC_KO'])) {
            $cookie = $_COOKIE['_SC_KO'];

            $shoppingCart = $this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId' => $cookie));


            if ($shoppingCart != null) {
                return $this->render('commonFiles/contact.html.twig', ['productsCart' => $shoppingCart]);
            }

        }

        return $this->render('commonFiles/contact.html.twig', ['productsCart' => null]);
    }

    /**
     * @Route("/search", name="search")
     */
    public function search()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $findedProducts = Array();
        $products = Array();
        if (isset($_GET['forSearch'])) {
            $search = $_GET['forSearch'];
            $products = $this->getDoctrine()->getRepository(Product::class)->findBy(array('title'=>$search));



        }
        if (isset($_COOKIE['_SC_KO'])) {
            $cookie = $_COOKIE['_SC_KO'];

            $shoppingCart = $this->getDoctrine()->getRepository(ShoppingCart::class)->findBy(array('coocieId' => $cookie));


            if ($shoppingCart != null) {
                return $this->render('home/search.html.twig', ['productsCart' => $shoppingCart, 'products' => $products, 'allCategories' => $categories]);

            }

        }

        return $this->render('home/search.html.twig', ['productsCart' => null, 'products' => $products, 'allCategories' => $categories]);
    }

}