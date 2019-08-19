<?php

namespace App\Action\Post;

use App\Service\PostService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class Index
{
    /** @var PostService $postService */
    protected $postService;
    /** @var View $view */
    protected $view;

    /**
     * Index constructor.
     * @param PostService $postService
     * @param View $view
     * @param Context $context
     */
    public function __construct(PostService $postService, View $view, Context $context)
    {
        $this->postService = $postService;
        $this->view = $view;

        $this->view->setContext(
            $context->addGroups(['default', 'post'])
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
     * @SWG\Tag(name="Post")
     *
     * @return View
     */
    public function __invoke(): View
    {
        return $this->view->setData(
            $this->postService->findAll()
        );
    }
}