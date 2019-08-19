<?php

namespace App\Action\Trend;

use App\Entity\Trend;
use App\Service\TrendService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class Show
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
     *     summary="Get a trend",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @Model(type=App\Entity\Trend::class, groups={"trend"})
     *    )
     * )
     * @SWG\Tag(name="Trend")
     *
     * @param Trend $trend
     * @return View
     */
    public function __invoke(Trend $trend): View
    {
        return $this->view->setData($trend);
    }
}