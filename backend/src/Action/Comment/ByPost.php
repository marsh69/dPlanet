<?php

namespace App\Action\Comment;

use App\Entity\Post;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class ByPost
{
    /** @var View $view */
    protected $view;

    /**
     * Index constructor.
     * @param View $view
     * @param Context $context
     */
    public function __construct(View $view, Context $context)
    {
        $this->view = $view;

        $this->view->setContext(
            $context->addGroups(['default', 'comment'])
        );
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
     * @SWG\Tag(name="Post")
     *
     * @param Post $post
     * @return View
     */
    public function __invoke(Post $post): View
    {
        return $this->view->setData($post->getComments());
    }
}