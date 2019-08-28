<?php

namespace App\Action\Notification;

use App\Entity\Notification;
use App\Service\NotificationService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class Show
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
     *     summary="Get a notification by its ID",
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
     * )
     * @SWG\Tag(name="Notification")
     *
     * @param Notification $notification
     * @return View
     */
    public function __invoke(Notification $notification): View
    {
        if (!$this->security->isGranted('show', $notification)) {
            return $this->view->setStatusCode(Response::HTTP_FORBIDDEN);
        };

        return $this->view->setData($notification);
    }
}
