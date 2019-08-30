<?php


namespace App\Service\Order;

use Doctrine\ORM\EntityManagerInterface;
class Service
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ){
        $this->entityManager = $entityManager;
    }
}