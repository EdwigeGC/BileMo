<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;


/**
 * Class ProductController
 * @Route("/api/products", name="api_products_")
 * @package App\Controller
 */
class ProductController extends AbstractController
{
    /**
     * Provide the list of all the product resources.
     *
     * @Route(name="products", methods={"GET"})
     * @param Paginator $paginator
     *
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Product"},
     *     security={"bearer"},
     *     @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="The collection page number",
     *          example= "1",
     *          @OA\Schema(
     *             type="integer",
     *             format="int32",
     *             minimum=1)
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Product list",
     *          @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Product"))
     *     ),
     *    @OA\Response(
     *       response=401,
     *       description="Invalid or missing token"
     *    ),
     * )
     * @return Response
     */
    public function list(Paginator $paginator): Response
    {
        $paginator->setEntityClass(Product::class);
        return $response= $this->json($paginator->getData(), 200, [],['groups'=>'products:list']);
    }

    /**
     * Provides one product resource.
     *
     * @Route("/{id}", name="product", methods={"GET"})
     * @param Product $product
     *
     * @OA\Get(
     *    path="/api/products/{id}",
     *    tags={"Product"},
     *    security={"bearer"},
     *    @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="product's Id",
     *          required=true,
     *          @OA\Schema(
     *             type="integer",
     *             format="int32")),
     *    @OA\Response(
     *       response="200",
     *       description="Product resource",
     *       @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Product"))
     *     ),
     *    @OA\Response(
     *       response=401,
     *       description="Invalid or missing token"
     *    ),
     *    @OA\Response(
     *       response=400,
     *       description="Invalid ID supplied"
     *    ),
     *    @OA\Response(
     *       response=404,
     *       description="The resource doesn't exist",
     *     ),
     *     security={
     *       "bearerAuth": {"JWT"}
     *     }
     * )
     * @return Response
     */
    public function details(Product $product): Response
    {
        if($product != null){
            return $response= $this->json($product, 200,[],['groups'=>'products:item']);
        }
        else{
            return new Response('Not found', 404);
        }
    }

}
