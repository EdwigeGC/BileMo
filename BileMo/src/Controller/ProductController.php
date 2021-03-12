<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;


class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $response= $this->json($productRepository->findAll(), 200, [],['groups'=>'list']);
    }
}
