<?php

namespace App\Action\Post\Likes;

use App\Entity\Post;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class Index
{
    /** @var View $view */
    protected $view;
    /** @var Security $security */
    protected $security;

    /**
     * Index constructor.
     * @param View $view
     * @param Context $context
     * @param Security $security
     */
    public function __construct(
        View $view,
        Context $context,
        Security $security
    ) {
        $this->view = $view;
        $this->security = $security;

        $this->view->setContext(
            $context->addGroups(['default', 'like'])
        );
    }

    /**
     * @SWG\Get(
     *     summary="Get the comments of a post",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @Model(type=App\Entity\Like::class, groups={"like"})
     *    )
     * )
     * @SWG\Tag(name="Post")
     *
     * @param Post $post
     * @return View
     */
    public function __invoke(Post $post): View
    {
        if (!$this->security->isGranted('list_likes', $post)) {
            return $this->view->setStatusCode(Response::HTTP_FORBIDDEN);
        };

        return $this->view->setData(
            $post->getLikes()
        );
    }
}
