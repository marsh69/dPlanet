<?php

namespace App\Action\Notification;

use App\Entity\Notification;
use App\Model\ApiListResponse;
use App\Service\NotificationService;
use Doctrine\ORM\NonUniqueResultException;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class Index
{
    /** @var NotificationService $notificationService */
    protected $notificationService;
    /** @var View $view */
    protected $view;
    /** @var Security $security */
    protected $security;

    /**
     * Index constructor.
     * @param NotificationService $notificationService
     * @param View $view
     * @param Context $context
     * @param Security $security
     */
    public function __construct(
        NotificationService $notificationService,
        View $view,
        Context $context,
        Security $security
    ) {
        $this->notificationService = $notificationService;
        $this->view = $view;
        $this->security = $security;

        $this->view->setContext(
            $context->addGroups(['default', 'notification'])
        );
    }

    /**
     * @SWG\Get(
     *     summary="Get all notifications",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @Model(type=App\Entity\Notification::class, groups={"notification"})
     *    ),
     *    @SWG\Response(
     *        response=403,
     *        description="Forbidden",
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
     * @SWG\Tag(name="Notification")
     *
     * @param Request $request
     * @return View
     * @throws NonUniqueResultException
     */
    public function __invoke(Request $request): View
    {
        if (!$this->security->isGranted('list', new Notification())) {
            return $this->view->setStatusCode(Response::HTTP_FORBIDDEN);
        }

        $limit = $request->query->get('limit');
        $offset = $request->query->get('offset');

        $response = new ApiListResponse(
            $this->notificationService->findAll($limit, $offset),
            $limit,
            $offset,
            $this->notificationService->getCount()
        );

        return $this->view->setData($response);
    }
}
