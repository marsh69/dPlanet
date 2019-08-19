<?php

namespace App\Action\Trend;

use App\Entity\Trend;
use App\Service\TrendService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class Delete
{
    /** @var TrendService $trendService */
    protected $trendService;
    /** @var View $view */
    protected $view;
    /** @var Security $security */
    protected $security;

    /**
     * Index constructor.
     * @param TrendService $trendService
     * @param View $view
     * @param Context $context
     * @param Security $security
     */
    public function __construct(
        TrendService $trendService,
        View $view,
        Context $context,
        Security $security
    ) {
        $this->trendService = $trendService;
        $this->view = $view;
        $this->security = $security;

        $this->view->setContext(
            $context->addGroups(['default', 'trend'])
        );
    }

    /**
     * @SWG\Delete(
     *     summary="Delete a trend",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @Model(type=App\Entity\Trend::class, groups={"trend"})
     *    ),
     *    @SWG\Response(
     *         response=403,
     *         description="Forbidden",
     *    )
     * )
     * @SWG\Tag(name="Trend")
     *
     * @param Trend $trend
     * @return View
     */
    public function __invoke(Trend $trend): View
    {
        if (!$this->security->isGranted('delete', $trend)) {
            return $this->view->setStatusCode(Response::HTTP_FORBIDDEN);
        };

        $this->trendService->delete($trend);

        return $this->view->setData($trend);
    }
}