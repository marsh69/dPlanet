<?php

namespace App\Action\Post\Comments;

use App\Entity\Post;
use App\Model\ApiListResponse;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

class Index
{
    /** @var View $view */
    protected $view;

    /**
     * Index constructor.
     * @param View $view
     * @param Context $context
     */
    public function __construct(View $view, Context $context)
    {
        $this->view = $view;

        $this->view->setContext(
            $context->addGroups(['default', 'comment'])
        );
    }

    /**
     * @SWG\Get(
     *     summary="Get the comments of a post",
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
     * @SWG\Tag(name="Post")
     *
     * @param Request $request
     * @param Post $post
     * @return View
     */
    public function __invoke(Request $request, Post $post): View
    {
        $limit = $request->query->get('limit');
        $offset = $request->query->get('offset');

        $comments = $post->getComments();

        $response = new ApiListResponse(
            array_slice($comments, $offset, $limit),
            $limit,
            $offset,
            count($comments)
        );

        return $this->view->setData($response);
    }
}
