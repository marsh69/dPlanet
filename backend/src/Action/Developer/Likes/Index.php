<?php

namespace App\Action\Developer\Likes;

use App\Entity\Developer;
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
     *     summary="Get the likes a developer",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @Model(type=App\Entity\Like::class, groups={"like"})
     *    )
     * )
     * @SWG\Tag(name="Developer")
     *
     * @param Developer $developer
     * @return View
     */
    public function __invoke(Developer $developer): View
    {
        if (!$this->security->isGranted('list_likes', $developer)) {
            return $this->view->setStatusCode(Response::HTTP_FORBIDDEN);
        };

        return $this->view->setData(
            $developer->getLikes()
        );
    }
}
