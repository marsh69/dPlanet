<?php

namespace App\Action\Post\Image;

use App\Entity\Post;
use App\Service\PostService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class Delete
{
    /** @var PostService $postService */
    protected $postService;
    /** @var View $view */
    protected $view;
    /** @var Security $security */
    protected $security;

    /**
     * Index constructor.
     * @param PostService $postService
     * @param View $view
     * @param Context $context
     * @param Security $security
     */
    public function __construct(
        PostService $postService,
        View $view,
        Context $context,
        Security $security
    ) {
        $this->postService = $postService;
        $this->view = $view;
        $this->security = $security;

        $this->view->setContext(
            $context->addGroups(['default', 'post'])
        );
    }

    /**
     * @SWG\Delete(
     *     summary="Delete a post",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @Model(type=App\Entity\Post::class, groups={"post"})
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="Forbidden",
     *     ),
     * )
     * @SWG\Tag(name="Post")
     *
     * @param Post $post
     * @return View
     */
    public function __invoke(Post $post): View
    {
        if (!$this->security->isGranted('delete', $post)) {
            return $this->view->setStatusCode(Response::HTTP_FORBIDDEN);
        };

        $post->setImage(null);

        $this->postService->save($post);

        return $this->view->setData($post);
    }
}
