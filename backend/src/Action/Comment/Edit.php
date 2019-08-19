<?php

namespace App\Action\Comment;

use App\Entity\Comment;
use App\Service\CommentService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class Edit
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
     * @ParamConverter("newComment", class="App\Entity\Comment", converter="fos_rest.request_body")
     * @SWG\Put(
     *     summary="Edit a comment to a post",
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
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
     *             @SWG\Property(property="body", type="string", example="I like this post a lot!"),
     *         )
     *     ),
     * )
     * @SWG\Tag(name="Comment")
     *
     * @param Comment $comment
     * @param Comment $newComment
     * @param ConstraintViolationListInterface $violationList
     * @return View
     */
    public function __invoke(Comment $comment, Comment $newComment, ConstraintViolationListInterface $violationList): View
    {
        if (!$this->security->isGranted('edit', $comment)) {
            return $this->view->setStatusCode(Response::HTTP_FORBIDDEN);
        };

        if ($violationList->count() > 0) {
            return $this->view
                ->setData($violationList)
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $comment->setBody($newComment->getBody());

        $this->commentService->save($comment);

        return $this->view->setData($comment);
    }
}
