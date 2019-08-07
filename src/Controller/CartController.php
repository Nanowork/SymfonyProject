<?php

namespace App\Controller;

use App\Entity\OrderItem;
use App\Form\ClearCartType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index()
    {
        $clearForm = $this->createForm(ClearCartType::class);
//        $setPaymentForm = $this->createForm(SetPaymentType::class, $order->getCurrent());
//        $setShipmentForm = $this->createForm(SetShipmentType::class, $order->getCurrent());
//        $setDiscountForm = $this->createForm(SetDiscountType::class, $order->getCurrent());


        return $this->render('cart/index.html.twig', [
            //'order' => $order,
            'clearForm' => $clearForm->createView(),
//            'setPaymentForm' => $setPaymentForm->createView(),
//            'setShipmentForm' => $setShipmentForm->createView(),
//            'setDiscountForm' => $setDiscountForm->createView(),
//            'itemsInCart' => $order->getCurrent()->getItemsTotal()
        ]);
    }

    /**
     * @Route("/cart/clear", name="cart.clear", methods={"POST"})
     */
    public function clear(Request $request): Response
    {
        $form = $this->createForm(ClearCartType::class);
        $form->handleRequest($request);

//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->orderFactory->clear();
//            $this->addFlash('success', $this->translator->trans('app.cart.clear.message.success'));
//        }

        return $this->redirectToRoute('index');
    }
}
