<?php

namespace App\Controller;

use App\Entity\Shipment;
use App\Form\AddShipmentType;
use App\Form\EditShipmentType;
use App\Repository\ShipmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

class ShipmentController extends AbstractController
{


    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
    FlashBagInterface $flashBag
    )
    {
        $this->flashBag = $flashBag;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/shipment", name="shipment")
     * @param ShipmentRepository $shipmentRepository
     * @return Response
     */
    public function index(ShipmentRepository $shipmentRepository): Response
    {
        $shipments = $shipmentRepository->findAll();

        return $this->render('shipment/index.html.twig', [
            'shipments' => $shipments
        ]);
    }

    /**
     * @Route("/addshipment", name="add_shipment")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function addShipment(Request $request)
    {
        $shipment = new Shipment();
        $shipment -> setCreatedAt(new \DateTime());
        $shipment -> setUpdatedAt(new \DateTime());

        $form = $this->createForm(
            AddShipmentType::class,
            $shipment
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($shipment);
            $this->entityManager->flush();

            return $this->redirectToRoute('shipment');
        }

        return $this->render('shipment/addShipment.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/editshipment/{id}", name="edit_shipment")
     * @param Shipment $shipment
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function editShipment(Shipment $shipment, Request $request)
    {
        $shipment -> setCreatedAt(new \DateTime());
        $shipment -> setUpdatedAt(new \DateTime());

        $form = $this->createForm(
            EditShipmentType::class,
            $shipment
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $shipment = $form->getData();
            $this->entityManager->persist($shipment);
            $this->entityManager->flush();

            return $this->redirectToRoute('shipment');
        }

        return $this->render('shipment/editShipment.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("deleteshipment/{id}", name="delete_shipment")
     * @param $id
     * @return Response
     */
  public function deleteShipment($id){
      $entityManager = $this->getDoctrine()->getManager();

      $shipment= $entityManager->getRepository(Shipment::class)->find($id);

      if(!$shipment){
          throw $this->createNotFoundException(
              'No record found for shipment with id :'.$id
          );
      }

      $entityManager->remove($shipment);
      $entityManager->flush();

      $this->flashBag->add('notice', 'Shipment was deleted!');

      return $this->redirectToRoute('shipment');

  }
}
