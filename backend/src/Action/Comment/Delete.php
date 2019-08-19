<?php

namespace App\Action\Comment;

use App\Entity\Comment;
use App\Service\CommentService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class Delete
{
    /** @var CommentService $commentService */
    protected $commentService;
    /** @var View $view */
    protected $view;
    /** @var Security $security */
    protected $security;

    /**
     * Index constructor.
     * @param CommentService $commentService
     * @param View $view
     * @param Context $context
     * @param Security $security
     */
    public function __construct(
        CommentService $commentService,
        View $view,
        Context $context,
        Security $security
    ) {
        $this->commentService = $commentService;
        $this->view = $view;
        $this->security = $security;

        $this->view->setContext(
            $context->addGroups(['default', 'comment'])
        );
    }

    /**
     * @SWG\Delete(
     *     summary="Delete a comment",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @Model(type=App\Entity\Comment::class, groups={"comment"})
     *    ),
     *    @SWG\Response(
     *         response=403,
     *         description="Forbidden",
     *    ),
     * )
     * @SWG\Tag(name="Comment")
     *
     * @param Comment $comment
     * @return View
     */
    public function __invoke(Comment $comment): View
    {
        if (!$this->security->isGranted('delete', $comment)) {
            return $this->view->setStatusCode(Response::HTTP_FORBIDDEN);
        };

        $this->security->isGranted('delete', $comment);

        $this->commentService->delete($comment);

        return $this->view->setData($comment)
            ->setStatusCode(Response::HTTP_OK);
    }
}
