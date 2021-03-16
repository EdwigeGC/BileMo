<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Customer;
use App\Repository\CustomerRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="users_client", methods={"GET"})
     * @param UserRepository $repository
     * @param Customer $client
     * @return Response
     */
    public function list(UserRepository $repository, Customer $client): Response
    {
        $users= $repository->findBy([],['client_id'=>$client]);
        return $response= $this->json($users, 200, [],['groups'=>'users']);
    }

    /**
     * @Route("/clients/{id}/users", name="users_client_details", methods={"GET"})
     * @param int $id
     * @param int $users_id
     * @param ClientRepository $repository
     * @return Response
     */
    public function details(int $id, int $users_id, UserRepository $repository): Response
    {
        $client = $repository->findOneBy(['id' =>$id]);
        return $response= $this->json($repository->findOneBy(['id' =>$id]), 200,[],['groups'=>'details']);
    }

    /**
     * @Route("user/new","user_create", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $manager
     * @param ValidatorInterface $validator
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager, ValidatorInterface $validator)
    {
        $req= $request->getContent();

        try{
            $user= $serializer->deserialize($req, User::class, 'json');

            $errors= $validator->validate($user);

            if(count($errors)>0) {
                return $this->json($errors, 400);
            }
            $manager->persist($user);
            $manager->flush();

            return $this->json($user, 201,[]);
        } catch(NotEncodableValueException $e){
            return $this->json([
                'status'=> 400,
                'message'=> $e->getMessage()
            ],400);
        }
    }
}

