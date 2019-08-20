<?php

namespace App\Action\Like;

use App\Entity\Like;
use App\Model\ApiListResponse;
use App\Service\LikeService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class Index
{
    /** @var LikeService $likeService */
    protected $likeService;
    /** @var View $view */
    protected $view;
    /** @var Security $security */
    protected $security;

    /**
     * Index constructor.
     * @param LikeService $likeService
     * @param View $view
     * @param Context $context
     * @param Security $security
     */
    public function __construct(
        LikeService $likeService,
        View $view,
        Context $context,
        Security $security
    ) {
        $this->likeService = $likeService;
        $this->view = $view;
        $this->security = $security;

        $this->view->setContext(
            $context->addGroups(['default', 'like'])
        );
    }

    /**
     * @SWG\Get(
     *     summary="Get all likes",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @Model(type=App\Entity\Like::class, groups={"like"})
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
     * @SWG\Tag(name="Like")
     *
     * @param Request $request
     * @return View
     */
    public function __invoke(Request $request): View
    {
        if (!$this->security->isGranted('list', new Like())) {
            return $this->view->setStatusCode(Response::HTTP_FORBIDDEN);
        }

        $limit = $request->query->get('limit');
        $offset = $request->query->get('offset');

        $response = new ApiListResponse(
            $this->likeService->findAll(),
            $limit,
            $offset,
            $this->likeService->getCount()
        );

        return $this->view->setData($response);
    }
}
