<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function profile(ProductRepository $productRepository): Response
    {
        $user = $this->getUser();
        $products = $productRepository->findByUser($user);

        return $this->render('profile/index.html.twig', [
            'products' => $products
        ]);
    }
}
