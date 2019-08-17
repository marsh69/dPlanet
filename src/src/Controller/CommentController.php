<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Service\CommentService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends AbstractFOSRestController
{
    /** @var CommentService $commentService*/
    protected $commentService;
    /** @var Context $defaultContext */
    protected $defaultContext;

    /**
     * CommentController constructor.
     * @param CommentService $commentService
     * @param Context $context
     */
    public function __construct(CommentService $commentService, Context $context)
    {
        $this->commentService = $commentService;
        $this->defaultContext = $context;

        $this->defaultContext->setGroups(['default', 'comment']);

    }

    /**
     * @param Request $request
     * @param Post $post
     * @return Response
     */
    public function create(Request $request, Post $post): Response
    {
        $comment = (new Comment())
            ->setPostedTo($post)
            ->setPostedBy($this->getUser());

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentService->save($comment);
            return new RedirectResponse("/posts/" . $post->getId());
        }

        $view = $this->view($form->getErrors())
            ->setTemplate('@App/comment/form.html.twig')
            ->setTemplateData([
                'post' => $post,
                'form' => $form->createView()
            ])
            ->setContext($this->defaultContext);

        return $this->handleView($view);
    }

    /**
     * @param Request $request
     * @param Comment $comment
     * @return Response
     */
    public function edit(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentService->save($comment);
            return new RedirectResponse("/posts/" . $comment->getPostedTo()->getId());
        }

        $view = $this->view($form->getErrors())
            ->setTemplate('@App/comment/form.html.twig')
            ->setTemplateData([
                'form' => $form->createView()
            ])
            ->setContext($this->defaultContext);

        return $this->handleView($view);
    }

    /**
     * @param Request $request
     * @param Comment $comment
     * @return Response
     */
    public function delete(Request $request, Comment $comment): Response
    {
        $this->commentService->delete($comment);
        return new RedirectResponse("/posts/" . $comment->getPostedTo()->getId());
    }
}
