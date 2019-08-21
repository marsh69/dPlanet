<?php

namespace App\Action\Comment;

use App\Model\ApiListResponse;
use App\Service\CommentService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

class Index
{
    /** @var CommentService $commentService */
    protected $commentService;
    /** @var View $view */
    protected $view;

    /**
     * Index constructor.
     * @param CommentService $commentService
     * @param View $view
     * @param Context $context
     */
    public function __construct(CommentService $commentService, View $view, Context $context)
    {
        $this->commentService = $commentService;
        $this->view = $view;

        $this->view->setContext(
            $context->addGroups(['default', 'comment'])
        );
    }

    /**
     * @SWG\Get(
     *     summary="Get all comments",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @Model(type=App\Entity\Comment::class, groups={"comment"})
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
     * @SWG\Tag(name="Comment")
     *
     * @param Request $request
     * @return View
     */
    public function __invoke(Request $request): View
    {
        $limit = $request->query->get('limit');
        $offset = $request->query->get('offset');

        $response = new ApiListResponse(
            $this->commentService->findAll($limit, $offset),
            $limit,
            $offset,
            $this->commentService->getCount()
        );

        return $this->view->setData($response);
    }
}
