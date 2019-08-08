<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController extends AbstractController
{
    /** @var PostService $postService */
    protected $postService;

    /**
     * PostController constructor.
     * @param PostService $postService
     */
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        return $this->render('@App/post/index.html.twig', [
            'posts' => $this->postService->findActive()
        ]);
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

        return $this->render('@App/post/form.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
            'title' => 'New post'
        ]);
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

        return $this->render('@App/post/form.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
            'title' => $post->getPostedBy()->getFirstName() . '\'s post'
        ]);
    }

    /**
     * @param Request $request
     * @param Post $post
     * @return Response
     */
    public function delete(Request $request, Post $post): Response
    {
        $this->postService->delete($post);
        return $this->redirectToRoute('posts.index');
    }
}
