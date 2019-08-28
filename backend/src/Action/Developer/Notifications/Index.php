<?php

namespace App\Action\Developer\Notifications;

use App\Entity\Developer;
use App\Model\ApiListResponse;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class Index
{
    /** @var View $view */
    protected $view;
    /** @var Security $security */
    protected $security;

    /**
     * Index constructor.
     * @param View $view
     * @param Context $context
     * @param Security $security
     */
    public function __construct(
        View $view,
        Context $context,
        Security $security
    ) {
        $this->view = $view;
        $this->security = $security;

        $this->view->setContext(
            $context->addGroups(['default', 'notification'])
        );
    }

    /**
     * @SWG\Get(
     *     summary="Get the notifications of a developer",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @Model(type=App\Entity\Notification::class, groups={"notification"})
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
     * @SWG\Tag(name="Developer")
     *
     * @param Request $request
     * @param Developer $developer
     * @return View
     */
    public function __invoke(Request $request, Developer $developer): View
    {
        if (!$this->security->isGranted('list_notifications', $developer)) {
            return $this->view->setStatusCode(Response::HTTP_FORBIDDEN);
        };

        $limit = $request->query->get('limit');
        $offset = $request->query->get('offset');

        $notifications = $developer->getReceivedNotifications();

        $response = new ApiListResponse(
            array_slice($notifications, $offset, $limit),
            $limit,
            $offset,
            count($notifications)
        );

        return $this->view->setData($response);
    }
}
