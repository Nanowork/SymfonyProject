<?php

namespace App\Controller;

use App\Entity\BasketItem;
use App\Entity\Product;
use App\Form\AddItemType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BasketController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param $criteria
     * @return object[]
     */
    private function getUserBasketItems
    ($criteria){
        $basketItemRepository = $this->entityManager->getRepository(BasketItem::class);
        $basketItems = $basketItemRepository->findBy($criteria);

        return $basketItems;
    }

    /**
     * @Route("/basket", name="basket")
     * @return Response
     */
    public function index(): Response
    {
        $basketItems = $this->getUserBasketItems([
            'user' => $this->getUser()]);

        $bItems = [];
        $total = 0;
        if(!empty($basketItems)){
            foreach ($basketItems as $item){
                $quantity = $item->getQuantity();
                $price = $item->getProduct()->getPrice();
                $sumPrice = $quantity * $price;

                $total += $sumPrice;

                $bItems[] = [
                    'id' => $item->getId(),
                    'name' => $item->getProduct()->getName(),
                    "price" => $price,
                    "quantity" => $quantity,
                    "sum_price" => $sumPrice
                ];
            }
        }

        return $this->render('basket/index.html.twig', [
            'basketItems' => $bItems,
            'total' => $total,
        ]);
    }

    public function addItemForm(Product $product): Response
    {
        $form = $this->createForm(AddItemType::class, $product);

        return $this->render('basket/addItemForm.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/basket/addItem/{id}", name="basket.addItem", methods={"POST"})
     */
    public function addItem(Request $request, Product $product): Response
    {
        $user = $this->getUser();
        $basketItem = new BasketItem();
        $basketItem->setUser($user);
        $basketItem->setProduct($product);

        $form = $this->createForm(
            AddItemType::class,
            $product
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $alreadyInBasket = $this->getUserBasketItems([
                'user' => $user,
                'product' => $product
            ]);

            if(empty($alreadyInBasket)){
                $basketItem->setQuantity(1);

                $this->entityManager->persist($basketItem);
                $this->entityManager->flush();
            } else {
                dd($alreadyInBasket);
                //TODO: show notification 'Already in basket'
                //TODO: redirect to products list
            }
        }

        return $this->redirectToRoute('basket');
    }

    /**
     * @Route("remove_basket_item/{id}", name="remove_basket_item")
     * @param $id
     * @return Response
     */
    public function deleteBasketItem($id)
    {
        $bItem = $this->entityManager->getRepository(BasketItem::class)->find($id);

        if (!$bItem) {
            throw $this->createNotFoundException(
                'No record found for basket item with id :' . $id
            );
        }

        $this->entityManager->remove($bItem);
        $this->entityManager->flush();

        return $this->redirectToRoute('basket');
    }
}
