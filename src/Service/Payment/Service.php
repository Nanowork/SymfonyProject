<?php


namespace App\Service\Payment;


use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;

class Service
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var PaymentRepository
     */
    private $repository;

    public function __construct(
        EntityManagerInterface $entityManager,
        PaymentRepository $repository
    ){
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    public function getPaymentById($id){
        $payment = $this->repository->findOneBy(['id' => $id]);

        return $payment;
    }
}