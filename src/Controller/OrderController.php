<?php

namespace App\Controller;

use App\Entity\CartOrder;
use App\Entity\OrderItem;
use App\Form\CreateOrderType;
use App\Repository\OrderItemRepository;
use App\Repository\OrderRepository;
use App\Service\Shipment\Service as ShipmentService;
use App\Service\Payment\Service as PaymentService;
use App\Service\Basket\Service as BasketService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ShipmentService
     */
    private $shipmentService;

    /**
     * @var BasketService
     */
    private $basketService;

    /**
     * @var PaymentService
     */
    private $paymentService;

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var OrderItemRepository
     */
    private $orderItemRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ShipmentService $shipmentService,
        PaymentService $paymentService,
        BasketService $basketService,
        OrderRepository $orderRepository,
        OrderItemRepository $orderItemRepository
    ){
        $this->entityManager = $entityManager;
        $this->shipmentService = $shipmentService;
        $this->paymentService = $paymentService;
        $this->basketService = $basketService;
        $this->orderRepository = $orderRepository;
        $this->orderItemRepository = $orderItemRepository;
    }

    /**
     * @Route("/orders", name="orders")
     * @param OrderRepository $orderRepository
     * @return Response
     */
    public function orders(OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();
        $orders = $orderRepository->findByUser($user);

        return $this->render('profile/order.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/details", name="details")
     * @param OrderItemRepository $orderItemRepository
     * @return Response
     */
    public function orderDetails(OrderItemRepository $orderItemRepository): Response
    {
        $orderId = $this->getOrder();
        $orderItems = $orderItemRepository->findById($orderId);

        return $this->render('order/orderDetails.html.twig', [
            'orderItems' => $orderItems
        ]);
    }

    public function createOrderForm(): Response
    {
        $form = $this->createForm(CreateOrderType::class);

        return $this->render('order/createOrderForm.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/order/createOrder/", name="order.createOrder", methods={"POST"})
     */
    public function createOrder(Request $request): Response
    {
        $postData = $request->request->get('create_order');
        $pId = $postData['payment'];
        $payment = $this->paymentService->getPaymentById($pId);

        $sId = $postData['shipment'];
        $shipment = $this->shipmentService->getShipemntById($sId);

        $user = $this->getUser();

        $basketItems = $this->basketService->getUserBasketItems([
            'user' => $user]);

        $orderItems = [];
        $totalPrice = 0;
        foreach ($basketItems as $bItem) {
            $oItem = new OrderItem();
            $oItem->setProduct($bItem->getProduct());
            $oItem->setQuantity($bItem->getQuantity());
            $oItem->setPrice($bItem->getProduct()->getPrice());

            $sumPrice = $oItem->getQuantity() * $oItem->getPrice();
            $oItem->setSumPrice($sumPrice);

            $totalPrice += $sumPrice;

            $orderItems[] = $oItem;
        }

        $order = new CartOrder();

        $order->setTotalPrice($totalPrice);
        $order->setCreatedOn(new \DateTime());
        $order->setUser($user);
        $order->setShipment($shipment);
        $order->setPayment($payment);

        $form = $this->createForm(
            CreateOrderType::class,
            $order
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($order);

            foreach ($orderItems as $orderI) {
                $orderI->setOrder($order);
                $this->entityManager->persist($orderI);
            }

            foreach($basketItems as $basketI){
                $this->entityManager->remove($basketI);
            }
            $this->entityManager->flush();

            return $this->redirectToRoute('index');
        }
    }
}
