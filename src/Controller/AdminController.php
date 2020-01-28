<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\OrderProduct;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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

        $user = $this->getUser();
        $product = new Product();
        $form = $this->createForm(Products::class, $product);
        $form->handleRequest($request);

        $producttoEdit = $this->getDoctrine()->getRepository(Product::class)->find($id);

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
            $em = $this->getDoctrine()->getManager();
            $em->persist($producttoEdit);
            $em->flush();
            return $this->redirectToRoute("adminPanel");
        };
        return $this->render("admin/editProduct.html.twig", ['producttoEdit' => $producttoEdit, 'user' => $user]);

    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/seeOrders", name="seeOrders")
     */
    public function seeOrder(Request $request)
    {

            $orders = $this->getDoctrine()->getRepository(OrderProduct::class)->findAll();
            $newOrders = Array();
            foreach ($orders as $order) {
                if ($order->getNewOrArchived() === false) {
                    array_push($newOrders, $order);
                }
            }


            return $this->render("admin/seeOrders.html.twig", ['orders' => $newOrders]);

    }

}