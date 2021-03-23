<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/user", name="api_user_")
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController
{
    /**
     * @Route ("/{id}/customers", name="list_customers", methods={"GET"})
     * @param CustomerRepository $repository
     * @param integer $id
     * @return JsonResponse
     */
    public function list(CustomerRepository $repository, int $id): JsonResponse
    {
        $customers=$repository->findBy(['users_id'=>$id],['lastName']);
        return $response= $this->json($customers, 200, [],['groups'=>'list']);
    }

    /**
     * @Route("/{id}", name="customers_item", methods={"GET"})
     * @param int $id
     * @param CustomerRepository $repository
     * @return Response
     */
    public function item(int $id, CustomerRepository $repository): Response
    {
        return $response= $this->json($repository->findOneBy(['id' =>$id]), 200,[],['groups'=>'item']);
    }
}