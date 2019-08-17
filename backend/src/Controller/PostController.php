<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Service\PostService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController extends AbstractFOSRestController
{
    /** @var PostService $postService */
    protected $postService;
    /** @var Context $defaultContext */
    protected $defaultContext;

    /**
     * PostController constructor.
     * @param PostService $postService
     * @param Context $context
     */
    public function __construct(PostService $postService, Context $context)
    {
        $this->postService = $postService;
        $this->defaultContext = $context;

        $this->defaultContext->setGroups(['default', 'post']);
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
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $posts = $this->postService->findActive();

        $view = $this->view($posts, 200)
            ->setContext($this->defaultContext);

        return $this->handleView($view);
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
     * @param Request $request
     * @param Post $post
     * @return Response
     */
    public function show(Request $request, Post $post): Response
    {
        $view = $this->view($post, 200)
            ->setContext($this->defaultContext);

        return $this->handleView($view);
    }

    /**
     * TODO: Api documentation
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $post = new Post();
        $post->setPostedBy($this->getUser());

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->postService->save($post);

            $view = $this->view($post, 200)
                ->setContext($this->defaultContext);

            return $this->handleView($view);
        }

        $view = $this->view($form->getErrors(), 400)
            ->setContext($this->defaultContext);

        return $this->handleView($view);
    }

    /**
     * TODO: Api documentation
     *
     * @param Request $request
     * @param Post $post
     * @return Response
     */
    public function edit(Request $request, Post $post): Response
    {
        $post->setPostedBy($this->getUser());

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->postService->save($post);

            $view = $this->view($post, 200)
                ->setContext($this->defaultContext);

            return $this->handleView($view);
        }

        $view = $this->view($form->getErrors(), 400)
            ->setContext($this->defaultContext);

        return $this->handleView($view);
    }

    /**
     * TODO: Api documentation
     *
     * @param Request $request
     * @param Post $post
     * @return Response
     */
    public function delete(Request $request, Post $post): Response
    {
        $this->postService->delete($post);

        $view = $this->view($post, 200)
            ->setContext($this->defaultContext);

        return $this->handleView($view);
    }
}
