<?php

namespace App\Action\Comment;

use App\Service\CommentService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

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
     *    )
     * )
     * @SWG\Tag(name="Comment")
     *
     * @return View
     */
    public function __invoke(): View
    {
        return $this->view->setData(
            $this->commentService->findBy(['isDeleted' => false])
        );
    }
}
