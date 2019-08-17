<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use http\Exception\BadMethodCallException;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends AbstractFOSRestController
{
    /**
     * @SWG\Post(
     *     security={},
     *     summary="Get an authentication token",
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *     ),
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         type="json",
     *         description="JSON Payload",
     *         format="application/json",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="username", type="string", example="admin"),
     *             @SWG\Property(property="password", type="string", example="admin")
     *         )
     *     ),
     * )
     *
     * @return Response
     */
    public function login(): Response
    {
        throw new BadMethodCallException('This method should not have been called! Is the firewall correctly installed?');
    }
}