<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ClientRepository;

class CustomerController extends AbstractController
{
    /**
     * @Route("/clients/{id}/users", name="users_client", methods={"GET"})
     * @param ClientRepository $repository
     * @param int $id
     * @return Response
     */
    public function list(CustomerRepository $repository, $id): Response
    {
        return $response= $this->json($repository->findOneBy(['id' =>$id]), 200, [],['groups'=>'users']);
    }

    /**
     * @Route("/clients/{id}/users", name="users_client_item", methods={"GET"})
     * @param int $id
     * @param int $users_id
     * @param CustomerRepository $repository
     * @return Response
     */
    public function item(int $id, int $users_id, CustomerRepository $repository): Response
    {
        $client = $repository->findOneBy(['id' =>$id]);
        return $response= $this->json($repository->findOneBy(['id' =>$id]), 200,[],['groups'=>'item']);
    }
}
