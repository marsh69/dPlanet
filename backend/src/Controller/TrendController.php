<?php

namespace App\Controller;

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

class TrendController
{
    /** @var TrendService $trendService */
    protected $trendService;
    /** @var Security $security */
    protected $security;
    /** @var View $view */
    protected $view;


    /**
     * TrendController constructor.
     * @param TrendService $trendService
     * @param Context $context
     * @param View $view
     * @param Security $security
     */
    public function __construct(TrendService $trendService, Context $context, View $view, Security $security)
    {
        $this->trendService = $trendService;
        $this->security = $security;

        $this->view = $view->setContext(
            $context->setGroups(['default', 'trend'])
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
     *
     * @return View
     */
    public function index(): View
    {
        $trends = $this->trendService->findAll();
        return $this->view->setData($trends);
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
     *
     * @param Trend $trend
     * @return View
     */
    public function show(Trend $trend): View
    {
        return $this->view->setData($trend);
    }

    /**
     * @ParamConverter("trend", class="App\Entity\Trend", converter="fos_rest.request_body")
     * @SWG\Post(
     *     summary="Create a trend",
     *     @SWG\Response(
     *        response=200,
     *        description="Success",
     *        @Model(type=App\Entity\Trend::class, groups={"trend"})
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
     *
     * @param Trend $trend
     * @param ConstraintViolationListInterface $violationList
     * @return View
     */
    public function create(Trend $trend, ConstraintViolationListInterface $violationList): View
    {
        if (!$this->security->isGranted('create', $trend)) {
            return $this->view->setStatusCode(Response::HTTP_UNAUTHORIZED);
        };

        if ($violationList->count() > 0) {
            return $this->view
                ->setData($violationList)
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $this->trendService->save($trend);

        return $this->view->setData($trend)
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @ParamConverter("newTrend", class="App\Entity\Trend", converter="fos_rest.request_body")
     *
     * @SWG\Patch(
     *     summary="Edit a trend",
     *     @SWG\Response(
     *        response=200,
     *        description="Success",
     *        @Model(type=App\Entity\Trend::class, groups={"trend"})
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
     *
     * @param Trend $trend
     * @param Trend $newTrend
     * @param ConstraintViolationListInterface $violationList
     * @return View
     */
    public function edit(Trend $trend, Trend $newTrend, ConstraintViolationListInterface $violationList): View
    {
        if (!$this->security->isGranted('edit', $trend)) {
            return $this->view->setStatusCode(Response::HTTP_UNAUTHORIZED);
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

    /**
     * @SWG\Delete(
     *     summary="Delete a trend",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @Model(type=App\Entity\Trend::class, groups={"trend"})
     *    )
     * )
     *
     * @param Trend $trend
     * @return View
     */
    public function delete(Trend $trend): View
    {
        if (!$this->security->isGranted('delete', $trend)) {
            return $this->view->setStatusCode(Response::HTTP_UNAUTHORIZED);
        };

        $this->trendService->delete($trend);

        return $this->view->setData($trend);
    }
}
