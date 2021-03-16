<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;


/**
 * Class ProductController
 * @Route("/api", name="api_")
 * @package App\Controller
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="products", methods={"GET"})
     */
    public function list(ProductRepository $repository): Response
    {
        return $response= $this->json($repository->findAll(), 200, [],['groups'=>'list']);
    }

    /**
     * @Route("/products/{id}", name="product", methods={"GET"})
     * @param Product $product
     * @return Response
     */
    public function details(Product $product): Response
    {
        if($product != null){
            return $response= $this->json($product, 200,[],['groups'=>'details']);
        }

        else{
            return new Response('Not found', 404);
        }
    }

}
