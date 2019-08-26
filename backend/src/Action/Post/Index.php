<?php

namespace App\Action\Post;

use App\Model\ApiListResponse;
use App\Service\PostService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

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
     *     ),
     *     @SWG\Parameter(
     *         name="limit",
     *         in="query",
     *         type="integer",
     *         description="Limit"
     *     ),
     *     @SWG\Parameter(
     *         name="offset",
     *         in="query",
     *         type="integer",
     *         description="Offset"
     *     ),
     * )
     * @SWG\Tag(name="Post")
     *
     * @param Request $request
     * @return View
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function __invoke(Request $request): View
    {
        $limit = $request->query->get('limit');
        $offset = $request->query->get('offset');

        $response = new ApiListResponse(
            $this->postService->findAll($limit, $offset),
            $limit,
            $offset,
            $this->postService->getCount()
        );

        return $this->view->setData($response);
    }
}
