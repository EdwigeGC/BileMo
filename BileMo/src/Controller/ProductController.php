<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use OpenApi\Annotations as OA;


/**
 * Class ProductController provides features to manage products
 * @Route("/api/products", name="api_products_")
 * @package App\Controller
 */
class ProductController extends AbstractController
{
    /**
     * Provide the list of all the product resources.
     *
     * @Route(name="list", methods={"GET"})
     * @param Paginator $paginator
     * @param SerializerInterface $serializer
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
    public function list(Paginator $paginator, SerializerInterface $serializer): Response
    {
        $paginator->setEntityClass(Product::class);
        $data=$serializer->serialize($paginator->getData(),"json",SerializationContext::create()->setGroups(array('list')));
        return new Response($data, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * Provides one product resource.
     *
     * @Route("/{id}", name="item", methods={"GET"})
     * @param Product $product
     * @param SerializerInterface $serializer
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
    public function details(Product $product,SerializerInterface $serializer): Response
    {
        if($product){
            $data=$serializer->serialize($product,"json", SerializationContext::create()->setGroups(array('item')));
            return new Response($data, 200, ['Content-Type' => 'application/json']);
        }
        else{
            return new Response("The resource doesn't exist", 404, ['Content-Type' => 'application/json']);
        }
    }

}
