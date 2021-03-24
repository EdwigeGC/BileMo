<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CustomerRepository;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\Paginator;
use OpenApi\Annotations as OA;


/**
 * Class CustomerController
 * @Route("/api/customers", name="api_customers_")
 * @package App\Controller
 */
class CustomerController extends AbstractController
{
    /**
     * Provides the list of customer resources for a specific user.
     *
     * @Route("/users/{user_id}",name="customers_list", methods={"GET"})
     * @param User $user_id
     * @OA\Get(
     *     path="/api/customers/users/{user_id}",
     *     tags={"Customer"},
     *     @OA\Parameter(
     *          name="user_id",
     *          in="path",
     *          description="Your user's ID",
     *          required=true,
     *          @OA\Schema(
     *             type="integer",
     *             format="int32")
     *      ),
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
     *          description="Customer list",
     *     ),
     * )
     * @return JsonResponse
     */
    public function list(User $user_id, Paginator $paginator): JsonResponse
    {
        $paginator  ->setEntityClass(Customer::class)
                    ->setFilterBy(['users'=>$user_id]);
        return $response= $this->json($paginator->getData(), 200, [],['groups'=>'list']);
    }

    /**
     * Provides one product resource for a specific user.
     *
     * @Route("/{id}", name="customers_item", methods={"GET"})
     * @param Customer $id
     * @param CustomerRepository $repository
     *
     * @OA\Get(
     *    path="/api/customers/{id}",
     *    tags={"Customer"},
     *    @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="customer's Id",
     *          required=true,
     *          @OA\Schema(
     *             type="integer",
     *             format="int32")
     *    ),
     *    @OA\Response(
     *       response="200",
     *       description="Customer resource",
     *       @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Customer"))
     *     ),
     *    @OA\Response(
     *        response=404,
     *        description="The resource doesn't exist"
     *     )
     * )
     * @return Response
     */
    public function item(Customer $id, CustomerRepository $repository): Response
    {
        if($id != null)
        {
            return $response = $this->json($repository->findOneBy(['id' => $id]), 200, [], ['groups' => 'item']);
        }
        else
        {
            return new Response('Not found', 404);
        }
    }

    /**
     * Create a new customer
     * @Route("/new", name="customers_create", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $manager
     * @param ValidatorInterface $validator
     *
     * @OA\Post(
     *    path="/api/customers/new",
     *    tags={"Customer"},
     *    @OA\Response(
     *       response="201",
     *       description="Customer resource created",
     *       @OA\JsonContent(type="object", @OA\Items(ref="#/components/schemas/Customer"))
     *     ),
     * )
     * @return JsonResponse
     */
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager, ValidatorInterface $validator) : JsonResponse
    {
        $req= $request->getContent();

        try{
            $customer= $serializer->deserialize($req, Customer::class, 'json');

            $errors= $validator->validate($customer);

            if(count($errors)>0) {
                return $this->json($errors, 400);
            }
            else{
                $customer->setCreatedAt(new \DateTime());
                $manager->persist($customer);
                $manager->flush();

                return $this->json($customer, 201,[]);
            }

        } catch(NotEncodableValueException $e){
            return $this->json([
                'status'=> 400,
                'message'=> $e->getMessage()
            ],400);
        }
    }

    /**
     * Delete a customer
     * @Route ("/delete/{id}", name="customers_delete", methods={"DELETE"})
     * @param Customer $customer
     * @param EntityManagerInterface $manager
     *
     * @OA\Delete(
     *     path="api/customers/delete/{id}",
     *     tags={"Customer"},
     *    @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="customer's Id",
     *          required=true,
     *          @OA\Schema(
     *             type="integer",
     *             format="int32")
     *    ),
     *    @OA\Response(
     *       response="204",
     *       description="Customer resource deleted"),
     *    @OA\Response(
     *       response="404",
     *       description="Resource not found"),
     * )
     * @return JsonResponse
     */
    public function delete(Customer $customer, EntityManagerInterface $manager)
    {
        if($customer != null) {
            $manager->remove($customer);
            $manager->flush();

            return new JsonResponse(null, 204);
        }
        else{
            return new JsonResponse(null, 404);
        }
    }

}
