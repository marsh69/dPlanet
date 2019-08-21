<?php

namespace App\Action\Post\Trends;

use App\Entity\Post;
use App\Model\ApiListResponse;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
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
            $context->addGroups(['default', 'trend'])
        );
    }

    /**
     * @SWG\Get(
     *     summary="Get the trends of a post",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @Model(type=App\Entity\Trend::class, groups={"trend"})
     *    ),
     *    @SWG\Parameter(
     *        name="limit",
     *        in="query",
     *        type="integer",
     *        description="Limit"
     *    ),
     *    @SWG\Parameter(
     *        name="offset",
     *        in="query",
     *        type="integer",
     *        description="Offset"
     *    ),
     * )
     * @SWG\Tag(name="Post")
     *
     * @param Request $request
     * @param Post $post
     * @return View
     */
    public function __invoke(Request $request, Post $post): View
    {
        $limit = $request->query->get('limit');
        $offset = $request->query->get('offset');

        $trends = $post->getTrends();

        $response = new ApiListResponse(
            array_slice($trends, $offset, $limit),
            $limit,
            $offset,
            count($trends)
        );

        return $this->view->setData($response);
    }
}
