<?php

namespace App\Action\Security;

use FOS\RestBundle\View\View;
use http\Exception\BadMethodCallException;
use Swagger\Annotations as SWG;

class Login
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
     * @SWG\Tag(name="Authentication")
     *
     * @return View
     */
    public function __invoke(): View
    {
        throw new BadMethodCallException('This method should not have been called! Is the firewall correctly installed?');
    }
}