<?php

namespace App\Action\Like;

use App\Entity\Like;
use App\Service\LikeService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class Index
{
    /** @var LikeService $likeService */
    protected $likeService;
    /** @var View $view */
    protected $view;
    /** @var Security $security */
    protected $security;

    /**
     * Index constructor.
     * @param LikeService $likeService
     * @param View $view
     * @param Context $context
     * @param Security $security
     */
    public function __construct(
        LikeService $likeService,
        View $view,
        Context $context,
        Security $security
    ) {
        $this->likeService = $likeService;
        $this->view = $view;
        $this->security = $security;

        $this->view->setContext(
            $context->addGroups(['default', 'like'])
        );
    }

    /**
     * @SWG\Get(
     *     summary="Get all likes",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @Model(type=App\Entity\Like::class, groups={"like"})
     *    ),
     *    @SWG\Response(
     *        response=403,
     *        description="Forbidden",
     *    ),
     * )
     * @SWG\Tag(name="Like")
     *
     * @return View
     */
    public function __invoke(): View
    {
        if (!$this->security->isGranted('list', new Like())) {
            return $this->view->setStatusCode(Response::HTTP_FORBIDDEN);
        }

        return $this->view->setData(
            $this->likeService->findAll()
        );
    }
}
