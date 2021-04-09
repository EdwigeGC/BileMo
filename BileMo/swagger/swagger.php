<?php

use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/doc", name="documentation", methods={"GET"})
 *
 * @OA\Info(
 *     title="BileMo",
 *     description="This API provides to BileMo's referenced user to access its products and manage their customers",
 *     version="1.0.0",
 *     )
 */