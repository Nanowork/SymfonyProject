<?php


namespace App\Service\Shipment;

use App\Repository\ShipmentRepository;
use Doctrine\ORM\EntityManagerInterface;

class Service
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ShipmentRepository
     */
    private $repository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ShipmentRepository $repository
    ){
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    public function getShipemntById($id){
        $shipment = $this->repository->findOneBy(['id' => $id]);

        return $shipment;
    }
}