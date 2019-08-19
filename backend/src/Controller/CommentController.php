<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Service\CommentService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class CommentController
{
    /** @var CommentService $commentService*/
    protected $commentService;
    /** @var View $view */
    protected $view;
    /** @var Security $security */
    protected $security;

    /**
     * CommentController constructor.
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
     * @SWG\Get(
     *     summary="Get all comments",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @Model(type=App\Entity\Comment::class, groups={"comment"})
     *    )
     * )
     *
     * @return View
     */
    public function list(): View
    {
        $comments = $this->commentService->findAll();
        return $this->view->setData($comments);
    }

    /**
     * @SWG\Get(
     *     summary="Get a comment",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @Model(type=App\Entity\Comment::class, groups={"comment"})
     *    )
     * )
     *
     * @param Comment $comment
     * @return View
     */
    public function show(Comment $comment): View
    {
        return $this->view->setData($comment);
    }

    /**
     * @SWG\Get(
     *     summary="Get the comments of a post",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @Model(type=App\Entity\Comment::class, groups={"comment"})
     *    )
     * )
     *
     * @param Post $post
     * @return View
     */
    public function byPost(Post $post): View
    {
        return $this->view->setData($post->getComments());
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
     *
     * @param Post $post
     * @param Comment $comment
     * @param ConstraintViolationListInterface $violationList
     * @return View
     */
    public function create(Post $post, Comment $comment, ConstraintViolationListInterface $violationList): View
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

    /**
     * @ParamConverter("newComment", class="App\Entity\Comment", converter="fos_rest.request_body")
     * @SWG\Put(
     *     summary="Edit a comment to a post",
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
     *
     * @param Comment $comment
     * @param Comment $newComment
     * @param ConstraintViolationListInterface $violationList
     * @return View
     */
    public function edit(Comment $comment, Comment $newComment, ConstraintViolationListInterface $violationList): View
    {
        if (!$this->security->isGranted('edit', $comment)) {
            return $this->view->setStatusCode(Response::HTTP_UNAUTHORIZED);
        };

        if ($violationList->count() > 0) {
            return $this->view
                ->setData($violationList)
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $comment->setBody($newComment->getBody());

        $this->commentService->save($comment);

        return $this->view->setData($comment)
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @SWG\Delete(
     *     summary="Delete a comment",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @Model(type=App\Entity\Comment::class, groups={"comment"})
     *    )
     * )
     *
     * @param Comment $comment
     * @return View
     */
    public function delete(Comment $comment): View
    {
        if (!$this->security->isGranted('delete', $comment)) {
            return $this->view->setStatusCode(Response::HTTP_UNAUTHORIZED);
        };

        $this->security->isGranted('delete', $comment);

        $this->commentService->delete($comment);

        return $this->view->setData($comment)
            ->setStatusCode(Response::HTTP_OK);
    }
}
