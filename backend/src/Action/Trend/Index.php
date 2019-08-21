<?php

namespace App\Action\Trend;

use App\Model\ApiListResponse;
use App\Service\TrendService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

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
     *    ),
     *    @SWG\Parameter(
     *        name="limit",
     *        in="query",
     *        type="integer",
     *        description="Limit"
     *    ),
     *    @SWG\Parameter(
     *        name="offset",
     *        in="query",
     *        type="integer",
     *        description="Offset"
     *    ),
     * )
     * @SWG\Tag(name="Trend")
     *
     * @param Request $request
     * @return View
     */
    public function __invoke(Request $request): View
    {
        $limit = $request->query->get('limit');
        $offset = $request->query->get('offset');

        $response = new ApiListResponse(
            $this->trendService->findAll(),
            $limit,
            $offset,
            $this->trendService->getCount()
        );

        return $this->view->setData($response);
    }
}
