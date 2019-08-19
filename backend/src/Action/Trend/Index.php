<?php

namespace App\Action\Trend;

use App\Service\TrendService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class Index
{
    /** @var TrendService $trendService */
    protected $trendService;
    /** @var View $view */
    protected $view;

    /**
     * Index constructor.
     * @param TrendService $trendService
     * @param View $view
     * @param Context $context
     */
    public function __construct(TrendService $trendService, View $view, Context $context)
    {
        $this->trendService = $trendService;
        $this->view = $view;

        $this->view->setContext(
            $context->addGroups(['default', 'trend'])
        );
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
     * @SWG\Tag(name="Trend")
     *
     * @return View
     */
    public function __invoke(): View
    {
        return $this->view->setData(
            $this->trendService->findAll()
        );
    }
}