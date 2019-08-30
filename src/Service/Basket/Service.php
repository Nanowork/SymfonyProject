<?php


namespace App\Service\Basket;


use App\Entity\BasketItem;
use Doctrine\ORM\EntityManagerInterface;

class Service
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
    public function getUserBasketItems($criteria)
    {
        $basketItemRepository = $this->entityManager->getRepository(BasketItem::class);
        $basketItems = $basketItemRepository->findBy($criteria);

        return $basketItems;
    }
}