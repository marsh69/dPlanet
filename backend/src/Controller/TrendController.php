<?php

namespace App\Controller;

use App\Service\TrendService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TrendController extends AbstractFOSRestController
{
    /** @var TrendService $trendService */
    protected $trendService;
    /** @var Context $defaultContext */
    protected $defaultContext;

    /**
     * TrendController constructor.
     * @param TrendService $trendService
     * @param Context $context
     */
    public function __construct(TrendService $trendService, Context $context)
    {
        $this->trendService = $trendService;
        $this->defaultContext = $context;

        $this->defaultContext->setGroups(['default', 'trend']);
    }

    /**
     * @SWG\Get(
     *     summary="Get all trends",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @Model(type=App\Entity\Trend::class, groups={"trend"})
     *    )
     * )
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $trends = $this->trendService->findActive();

        $view = $this->view($trends, 200)
            ->setContext($this->defaultContext);

        return $this->handleView($view);
    }
}
