<?php

namespace App\Action\Comment;

use App\Entity\Comment;
use App\Entity\Post;
use App\Service\CommentService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class Create
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
     * @ParamConverter("comment", class="App\Entity\Comment", converter="fos_rest.request_body")
     * @SWG\Post(
     *     summary="Add a comment to a post",
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
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
     * @param Post $post
     * @param Comment $comment
     * @param ConstraintViolationListInterface $violationList
     * @return View
     */
    public function __invoke(Post $post, Comment $comment, ConstraintViolationListInterface $violationList): View
    {
        if ($violationList->count() > 0) {
            return $this->view
                ->setData($violationList)
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $comment->setPostedTo($post)
            ->setPostedBy($this->security->getUser());

        $this->commentService->save($comment);

        return $this->view->setData($comment)
            ->setStatusCode(Response::HTTP_CREATED);
    }
}