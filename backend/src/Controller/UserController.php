<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Exception\NotImplementedException;

class UserController extends AbstractFOSRestController
{
    /**
     * @SWG\Get(
     *     summary="Get all users",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @Model(type=App\Entity\Trend::class, groups={"user"})
     *    )
     * )
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        throw new NotImplementedException('This controller is not yet done');
    }
}
