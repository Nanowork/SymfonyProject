<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Form\AddPaymentType;
use App\Form\EditPaymentType;
use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
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
     * @Route("/payment", name="payment")
     * @param PaymentRepository $paymentRepository
     * @return Response
     */
    public function index(PaymentRepository $paymentRepository): Response
    {
        $payments = $paymentRepository->findAll();

        return $this->render('payment/index.html.twig', [
            'payments' => $payments
        ]);
    }

    /**
     * @Route("/addpayment", name="add_payment")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function addPayment(Request $request)
    {
        $payment = new Payment();
        $payment->setCreatedAt(new \DateTime());
        $payment->setUpdatedAt(new \DateTime());

        $form = $this->createForm(
            AddPaymentType::class,
            $payment
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($payment);
            $this->entityManager->flush();

            return $this->redirectToRoute('payment');
        }

        return $this->render('payment/addPayment.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/editpayment/{id}", name="edit_payment")
     * @param Payment $payment
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function editShipment(Payment $payment, Request $request)
    {
        $payment -> setCreatedAt(new \DateTime());
        $payment -> setUpdatedAt(new \DateTime());

        $form = $this->createForm(
            EditPaymentType::class,
            $payment
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $payment = $form->getData();
            $this->entityManager->persist($payment);
            $this->entityManager->flush();

            return $this->redirectToRoute('payment');
        }

        return $this->render('payment/editPayment.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("deletepayment/{id}", name="delete_payment")
     * @param $id
     * @return Response
     */
    public function deletePayment($id){
        $entityManager = $this->getDoctrine()->getManager();

        $payment= $entityManager->getRepository(Payment::class)->find($id);

        if(!$payment){
            throw $this->createNotFoundException(
                'No record found for payment with id :'.$id
            );
        }

        $entityManager->remove($payment);
        $entityManager->flush();

        return $this->redirectToRoute('payment');

    }
}
