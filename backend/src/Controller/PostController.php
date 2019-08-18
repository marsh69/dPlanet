<?php

namespace App\Controller;

use App\Entity\Post;
use App\Service\PostService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class PostController
{
    /** @var PostService $postService */
    protected $postService;
    /** @var View $view */
    protected $view;
    /** @var Security $security */
    protected $security;

    /**
     * PostController constructor.
     * @param PostService $postService
     * @param Context $context
     * @param View $view
     * @param Security $security
     */
    public function __construct(
        PostService $postService,
        Context $context,
        View $view,
        Security $security
    ) {
        $this->postService = $postService;
        $this->view = $view;
        $this->security = $security;

        $this->view->setContext(
            $context->setGroups(['default', 'post'])
        );
    }

    /**
     * @SWG\Get(
     *     summary="Get all posts",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @Model(type=App\Entity\Post::class, groups={"post"})
     *    )
     * )
     *
     * @return View
     */
    public function index(): View
    {
        $posts = $this->postService->findAll();
        return $this->view->setData($posts);
    }

    /**
     * @SWG\Get(
     *     summary="Get a post by its ID",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @Model(type=App\Entity\Post::class, groups={"post"})
     *    )
     * )
     *
     * @param Post $post
     * @return View
     */
    public function show(Post $post): View
    {
        return $this->view->setData($post);
    }

    /**
     * @ParamConverter("post", class="App\Entity\Post", converter="fos_rest.request_body")
     *
     * @SWG\Post(
     *     summary="Add a post",
     *     @SWG\Response(
     *        response=200,
     *        description="Success",
     *        @Model(type=App\Entity\Post::class, groups={"post"})
     *     ),
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         type="json",
     *         description="JSON Payload",
     *         format="application/json",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="body", type="string", example="I like this platform a lot!"),
     *             @SWG\Property(property="image", type="string"),
     *         )
     *     ),
     * )
     *
     *
     * @param Post $post
     * @param ConstraintViolationListInterface $violationList
     * @return View
     */
    public function create(Post $post, ConstraintViolationListInterface $violationList): View
    {
        if ($violationList->count() > 0) {
            return $this->view
                ->setData($violationList)
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $post->setPostedBy($this->security->getUser());

        $this->postService->save($post);

        return $this->view->setData($post)
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @ParamConverter("newPost", class="App\Entity\Post", converter="fos_rest.request_body")
     *
     * @SWG\Patch(
     *     summary="Edit a post",
     *     @SWG\Response(
     *        response=200,
     *        description="Success",
     *        @Model(type=App\Entity\Post::class, groups={"post"})
     *     ),
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         type="json",
     *         description="JSON Payload",
     *         format="application/json",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="body", type="string", example="I like this platform a lot!"),
     *         )
     *     ),
     * )
     *
     * @param Post $post
     * @param Post $newPost
     * @param ConstraintViolationListInterface $violationList
     * @return View
     */
    public function edit(Post $post, Post $newPost, ConstraintViolationListInterface $violationList): View
    {
        if (!$this->security->isGranted('edit', $post)) {
            return $this->view->setStatusCode(Response::HTTP_UNAUTHORIZED);
        };

        if ($violationList->count() > 0) {
            return $this->view
                ->setData($violationList)
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $post->setBody($newPost->getBody());

        $this->postService->save($post);

        return $this->view->setData($post)
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @SWG\Delete(
     *     summary="Delete a post",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @Model(type=App\Entity\Post::class, groups={"post"})
     *    )
     * )
     *
     * @param Post $post
     * @return View
     */
    public function delete(Post $post): View
    {
        if (!$this->security->isGranted('delete', $post)) {
            return $this->view->setStatusCode(Response::HTTP_UNAUTHORIZED);
        };

        $this->postService->delete($post);

        return $this->view->setData($post)
            ->setStatusCode(Response::HTTP_OK);
    }
}
