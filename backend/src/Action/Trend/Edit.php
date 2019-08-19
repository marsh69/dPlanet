<?php

namespace App\Action\Trend;

use App\Entity\Trend;
use App\Service\TrendService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class Edit
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
     * @ParamConverter("newTrend", class="App\Entity\Trend", converter="fos_rest.request_body")
     *
     * @SWG\Put(
     *     summary="Edit a trend",
     *     @SWG\Response(
     *        response=200,
     *        description="Success",
     *        @Model(type=App\Entity\Trend::class, groups={"trend"})
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="Forbidden",
     *     ),
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         type="json",
     *         description="JSON Payload",
     *         format="application/json",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="name", type="string", example="Symfony"),
     *         )
     *     ),
     * )
     * @SWG\Tag(name="Trend")
     *
     * @param Trend $trend
     * @param Trend $newTrend
     * @param ConstraintViolationListInterface $violationList
     * @return View
     */
    public function __invoke(Trend $trend, Trend $newTrend, ConstraintViolationListInterface $violationList): View
    {
        if (!$this->security->isGranted('edit', $trend)) {
            return $this->view->setStatusCode(Response::HTTP_FORBIDDEN);
        };

        if ($violationList->count() > 0) {
            return $this->view
                ->setData($violationList)
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $trend->setName($newTrend->getName());

        $this->trendService->save($trend);

        return $this->view->setData($trend)
            ->setStatusCode(Response::HTTP_OK);
    }
}
