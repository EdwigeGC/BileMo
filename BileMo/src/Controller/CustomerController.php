<?php

namespace App\Controller;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CustomerRepository;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\Paginator;
use OpenApi\Annotations as OA;


/**
 * Class CustomerController provides features to manage customers
 *
 * @Route("/api/customers", name="api_customers_")
 * @package App\Controller
 */
class CustomerController extends AbstractController
{
    /**
     * Provides the list of customer resources for a specific user.
     *
     * @Route(name="list", methods={"GET"})
     * @param Paginator $paginator
     * @param SerializerInterface $serializer
     *
     * @OA\Get(
     *     path="/api/customers",
     *     tags={"Customer"},
     *     security={"bearer"},
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
     *          @OA\JsonContent(type="array",@OA\Items(ref="#/components/schemas/Customer"))
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
        $user= $this->getUser();
        $paginator  ->setEntityClass(Customer::class)
                    ->setFilterBy(['user'=>$user]);
        $data= $serializer->serialize($paginator->getData(),"json",SerializationContext::create()->setGroups(array('list')));

        return new Response($data, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * Provides one customer resource for a specific user.
     *
     * @Route("/{id}", name="item", methods={"GET"})
     * @param Customer $customer
     * @param CustomerRepository $repository
     * @param SerializerInterface $serializer
     *
     * @OA\Get(
     *    path="/api/customers/{id}",
     *    tags={"Customer"},
     *    security={"bearer"},
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
     *       response=400,
     *       description="Invalid ID supplied"
     *    ),
     *    @OA\Response(
     *       response=401,
     *       description="Invalid or missing token"
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="The resource doesn't exist"
     *     )
     * )
     * @return Response
     */
    public function item(Customer $customer, CustomerRepository $repository, SerializerInterface $serializer): Response
    {
        $user= $this->getUser();
        if($customer && $customer->getUser() == $user){
            $data=$serializer->serialize($customer, "json",SerializationContext::create()->setGroups(array('item')));
            return new Response($data, 200, ['Content-Type' => 'application/json']);
        }
        else if($customer && $customer->getUser() != $user){
            return new Response("You can't access to this resource", 403);
        }
        else {
            return new Response("The resource doesn't exist", 404, ['Content-Type' => 'application/json']);
        }
    }

    /**
     * Create a new customer
     *
     * @Route("/new", name="create", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $manager
     * @param ValidatorInterface $validator
     *
     * @OA\Post(
     *    path="/api/customers/new",
     *    tags={"Customer"},
     *    security={"bearer"},
     *    @OA\Response(
     *       response="201",
     *       description="Customer resource created",
     *       @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Customer"))
     *     ),
     *     @OA\Response(
     *       response=400,
     *       description="Invalid data recorded"
     *    ),
     *    @OA\Response(
     *       response=401,
     *       description="Invalid or missing token"
     *    ),
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Customer"))
     *     )
     * )
     * @return Response
     */
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager, ValidatorInterface $validator) : Response
    {
        $data= $request->getContent();
        try{
            $customer= $serializer->deserialize($data, Customer::class, 'json');
            $user=$this->getUser();

            $errors= $validator->validate($customer);

            if(count($errors)>0) {
                return $this->json($errors, 400);
            }
            else{
                $customer->setCreatedAt(new \DateTime())
                        ->setUser($user);
                $manager->persist($customer);
                $manager->flush();

                $customer=$serializer->serialize($customer, "json",SerializationContext::create()->setGroups(array('item')));

                return new Response($customer, 201, ['Content-Type' => 'application/json']);
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
     *
     * @Route ("/{id}/delete", name="delete", methods={"DELETE"})
     * @param Customer $customer
     * @param EntityManagerInterface $manager
     *
     * @OA\Delete(
     *     path="api/customers/{id}/delete",
     *     tags={"Customer"},
     *     security={"bearer"},
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
     *       response=400,
     *       description="Invalid ID supplied"
     *    ),
     *    @OA\Response(
     *       response=401,
     *       description="Invalid or missing token"
     *    ),
     *    @OA\Response(
     *       response="404",
     *       description="Resource not found"),
     * )
     * @return JsonResponse|Response
     */
    public function delete(Customer $customer, EntityManagerInterface $manager)
    {
        if($customer && $customer->getUser() == $this->getUser()){
            $manager->remove($customer);
            $manager->flush();

            return new JsonResponse(null, 204);
        }
        else if($customer && $customer->getUser() != $this->getUser()){
            return new Response("You can't access to this resource", 403, ['Content-Type' => 'application/json']);
        }
        else{
            return new Response("Resource not found", 404);
        }
    }

    /**
     * Edit customer's information
     * @Route("/{id}/edit", name="edit", methods={"PUT"})
     *
     * @param Customer $customer
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     *
     * @OA\Put(
     *    path="/api/customers/{id}/edit",
     *    tags={"Customer"},
     *    security={"bearer"},
     *    @OA\Response(
     *       response="200",
     *       description="Customer resource updated",
     *       @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Customer"))
     *     ),
     *     @OA\Response(
     *       response=400,
     *       description="Invalid data recorded"
     *    ),
     *    @OA\Response(
     *       response=401,
     *       description="Invalid or missing token"
     *    ),
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Customer"))
     *     )
     * )
     *
     * @return JsonResponse
     */
    public function edit(Customer $customer, Request $request, EntityManagerInterface $manager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $data= $request->getContent();
        try{
            $content= $serializer->deserialize($data, Customer::class, 'json');
            $customer->getId($content->getId());

            $errors= $validator->validate($customer);

            if(count($errors)>0) {
                return $this->json($errors, 400);
            }
            else {
                $customer->setEmail($content->getEmail())
                    ->setLastName($content->getLastName())
                    ->setFirstName($content->getFirstName());
                $manager->persist($customer);
                $manager->flush();

                return $this->json($customer, 200, [], ['groups' => 'customers:item']);
            }
        } catch(NotEncodableValueException $e){
            return $this->json([
                'status'=> 400,
                'message'=> $e->getMessage()
            ],400);
        }
    }

}
