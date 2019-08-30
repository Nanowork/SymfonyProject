<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\CreateType;
use App\Form\EditProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Shipment\Service as ShipmentService;

class ProductController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ShipmentService
     */
    private $shipmentService;

    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/product", name="product")
     */
    public function index()
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * @Route("/create", name="create_product")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function create(Request $request)
    {
        $user = $this->getUser();
        $product = new Product();
        $product->setUser($user);
        $product->setCreatedAt(new \DateTime());
        $product->setUpdatedAt(new \DateTime());
        $form = $this->createForm(
            CreateType::class,
            $product
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($product);
            $this->entityManager->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('product/createProduct.html.twig',
            ['form' => $form->createView()]
        );
    }


    /**
     * @Route("/editproduct/{id}", name="edit_product")
     * @param Product $product
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function editProduct(Product $product, Request $request)
    {
        $product->setCreatedAt(new \DateTime());
        $product->setUpdatedAt(new \DateTime());

        $form = $this->createForm(
            EditProductType::class,
            $product
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product = $form->getData();
            $this->entityManager->persist($product);
            $this->entityManager->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('product/editProduct.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("deleteproduct/{id}", name="delete_product")
     * @param $id
     * @return Response
     */
    public function deleteProduct($id)
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No record found for product with id :' . $id
            );
        }

        $this->entityManager->remove($product);
        $this->entityManager->flush();

        return $this->redirectToRoute('index');

    }
}
