<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Service\PostService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $posts = $this->postService->findActive();

        $view = $this->view($posts, 200)
            ->setTemplateData(['posts' => $posts])
            ->setTemplate('@App/post/index.html.twig')
            ->setContext($this->defaultContext);

        return $this->handleView($view);
    }

    /**
     * @param Request $request
     * @param Post $post
     * @return Response
     */
    public function show(Request $request, Post $post): Response
    {
        $view = $this->view($post, 200)
            ->setTemplateData(['post' => $post])
            ->setContext($this->defaultContext);

        return $this->handleView($view);
    }

    /**
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
            return $this->redirectToRoute('posts.index');
        }

        $view = $this->view($form->getErrors(), 400)
            ->setTemplateData([
                'form' => $form->createView(),
                'post' => $post,
            ])
            ->setTemplate('@App/post/form.html.twig')
            ->setContext($this->defaultContext);

        return $this->handleView($view);
    }

    /**
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
            return $this->redirectToRoute('posts.index');
        }

        $view = $this->view($form->getErrors(), 400)
            ->setTemplateData([
                'form' => $form->createView(),
                'post' => $post,
            ])
            ->setTemplate('@App/post/form.html.twig')
            ->setContext($this->defaultContext);

        return $this->handleView($view);
    }

    /**
     * @param Request $request
     * @param Post $post
     * @return Response
     */
    public function delete(Request $request, Post $post): Response
    {
        $this->postService->delete($post);

        $view = $this->redirectView('/posts')
            ->setData($post)
            ->setContext($this->defaultContext);

        return $this->handleView($view);
    }
}
