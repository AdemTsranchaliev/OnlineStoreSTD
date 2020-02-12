<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\OrderProduct;
use App\Entity\User;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Product;
use App\Form\Products;
use App\Repository\ProductRepository;

class AdminController extends AbstractController
{

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/adminPanel", name="adminPanel")
     */
    public function adminPanel()
    {

        $models = $this->getDoctrine()->getRepository(Product::class)->findAll();
        return $this->render("admin/adminPanel.html.twig", ['models' => $models]);

    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/seeUsers", name="seeUsers")
     */
    public function seeUsers()
    {

        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render("admin/seeUsers.html.twig", ['users' => $users]);

    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/addProduct", name="addProduct")
     */
    public function addProduct(Request $request)
    {
        $user = $this->getUser();
        $product = new Product();
        $form = $this->createForm(Products::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
            foreach ($products as $prd) {
                if ($prd->getModelNumber() == $product->getModelNumber()) {
                    return $this->redirectToRoute('profile');
                }
            }

            $product->setBoughtCounter(0);

            $img1 = "D:/OnlineStoreSTD/public/img/uploads/";

            $img = 0;
            for ($i = 0; $i < count($_FILES['file']['tmp_name']); $i++) {
                if ($_FILES['file']['tmp_name'][$i] != '') {
                    $img++;
                }
            }
            $product->setPhotoCount($img);
            $product->setIsDetelet(0);
            $product->setIsPromotion(0);
            $product->setDiscountPrice(0);
            $product->setIsShoe(0);



            $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();


            $op=new Category();
            foreach ($categories as $value)
            {
                if (strcmp($product->getCategory(), $value->getTag()) == 0)
                {
                    $op=$value;
                    break;
                }
            }


            $product->setCategoryId($op);
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            $img = 0;
            for ($i = 0; $i < count($_FILES['file']['tmp_name']); $i++) {
                $targetfile = $img1 . $product->getId() . "." . $i . ".jpg";
                if ($_FILES['file']['tmp_name'][$i] != '') {
                    move_uploaded_file($_FILES['file']['tmp_name'][$img], $targetfile);
                    $img++;
                }
            }
            return $this->render("admin/addModel.html.twig", ['user' => $user]);
        };
        return $this->render("admin/addModel.html.twig", ['user' => $user]);

    }
    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $id
     * @Route("/editModel/{id}", name="editModel")
     */
    public function editModel(Request $request, $id)
    {
        $producttoEdit = $this->getDoctrine()->getRepository(Product::class)->find($id);

         if ($producttoEdit==null)
         {
             return $this->render("commonFiles/404.html.twig");
         }

        $product = new Product();
        $form = $this->createForm(Products::class, $product);
        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            $producttoEdit->setTitle($product->getTitle());
            $producttoEdit->setModelNumber($product->getModelNumber());
            $producttoEdit->setColor($product->getColor());
            $producttoEdit->setPrice($product->getPrice());
            $producttoEdit->setCategory($product->getCategory());
            $producttoEdit->setSizes($product->getSizes());
            $producttoEdit->setDiscountPrice($product->getDiscountPrice());
            $producttoEdit->setIsPromotion($product->getIsPromotion());
            $producttoEdit->setDescription($product->getDescription());

            $category = $this->getDoctrine()->getRepository(Category::class)-> findOneBy(array('tag' => $product->getCategory()));

            $producttoEdit->setCategoryId($category);
            $em = $this->getDoctrine()->getManager();
            $em->persist($producttoEdit);
            $em->flush();
            return $this->redirectToRoute("adminPanel");
        };
        return $this->render("admin/editProduct.html.twig", ['producttoEdit' => $producttoEdit]);

    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/seeOrders/{func}", name="seeOrders")
     * @param $func
     */
    public function seeOrders(Request $request,$func)
    {

        $orders = $this->getDoctrine()->getRepository(OrderProduct::class)->findAll();
        $newOrders = Array();
        $title='';
        if (strcmp($func,'all')==0)
        {
            $newOrders=$orders;
            $title='ВСИЧКИ ПОРЪЧКИ';
        }
        if (strcmp($func,'archived')==0)
        {
            foreach ($orders as $order) {
                if ($order->getNewOrArchived() === true) {
                    array_push($newOrders, $order);
                }
            }
            $title='ИЗПЪЛНЕНИ ПОРЪЧКИ';
        }
        if (strcmp($func,'new')==0)
        {
            foreach ($orders as $order) {
                if ($order->getConfirmed() === false) {
                    array_push($newOrders, $order);
                }
            }
            $title='НОВИ ПОРЪЧКИ';
        }
        if (strcmp($func,'confirmed')==0)
        {
            foreach ($orders as $order) {
                if ($order->getConfirmed() === true&&$order->getNewOrArchived() === false) {
                    array_push($newOrders, $order);
                }
            }
            $title='ПОТВЪРДЕНИ ПОРЪЧКИ';
        }



        return $this->render("admin/seeOrders.html.twig", ['orders' => $newOrders,'title'=>$title]);

    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/seeOrder/{id}", name="seeOrder")
     * @param $id
     */
    public function seeOrder(Request $request, $id)
    {

        $order = $this->getDoctrine()->getRepository(OrderProduct::class)->find($id);



        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            if($order->getConfirmed()==0)
            {
                $order->setConfirmed(true);
            }
            else
            {
                $order->setNewOrArchived(true);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();

        }
        return $this->render("admin/seeOrder.html.twig", ['order' => $order]);

    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/deleteModel", name="deleteModel")
     * @param $id
     */
    public function deleteModel()
    {
       if (isset($_POST['deleteModelId']))
       {
           $model = $this->getDoctrine()->getRepository(Product::class)->find($_POST['deleteModelId']);

           $model->setIsDetelet(1);
           $em = $this->getDoctrine()->getManager();
           $em->persist($model);
           $em->flush();


       }

        return $this->redirect("adminPanel");

    }


    /**
     * @Route("/student/ajax")
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
                                <a href=\"\singleProduct".$prd->getId()."\" class=\"product__link\">
                                    <img src=\"/img/uploads/".$prd->getId().".0.jpg\" alt=\"\" class=\"product__img\" id=\"img_1B\" height=\"400px\"> ";
                              if ($prd->getPhotoCount()>1)
                              {
                                  $ready.="  <img src=\"/img/uploads/".$prd->getId().".1.jpg\" alt=\"\" class=\"product__img-back\" id=\"img_1S\" height=\"400px\">";
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
                if($i%4==0)
                {
                    $ready.=" <div class=\"w-100\"></div>";
                }
                $i++;

            }
            $ready.=" </div>";
            file_put_contents('C:\Users\Asus\Desktop\untitled1\text.txt', $ready);
            return new Response($ready);
        } else {
            return $this->render('student/ajax.html.twig');
        }
    }

}