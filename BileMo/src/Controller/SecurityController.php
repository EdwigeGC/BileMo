<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use OpenApi\Annotations as OA;


class SecurityController extends AbstractController
{
    /**
     * Provides new token thanks to basic authentication
     *
     * @Route("/api/login_check", name="api_login")
     *
     * @OA\Post(
     *    path="/api/login_check",
     *    tags={"Authentication"},
     *    @OA\Response(
     *       response="200",
     *       description="new valid token",
     *       @OA\JsonContent(type="string")
     *     ),
     *    @OA\Response(
     *       response=400,
     *       description="Failure: bad username or password"
     *    ),
     * )
     * @return JsonResponse
     */
    public function api_login(): JsonResponse
    {
        $user = $this->getUser();

        return new JsonResponse([
            'email' => $user->getUsername(),
            'roles' => $user->getRoles(),
        ]);
    }

}
