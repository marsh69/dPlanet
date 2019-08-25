<?php

namespace App\Action\Developer;

use App\Entity\Developer;
use App\Model\ApiListResponse;
use App\Service\DeveloperService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class Index
{
    /** @var DeveloperService $developerService */
    protected $developerService;
    /** @var View $view */
    protected $view;
    /** @var Security $security */
    protected $security;

    /**
     * Index constructor.
     * @param DeveloperService $developerService
     * @param View $view
     * @param Context $context
     * @param Security $security
     */
    public function __construct(
        DeveloperService $developerService,
        View $view,
        Context $context,
        Security $security
    ) {
        $this->developerService = $developerService;
        $this->view = $view;
        $this->security = $security;

        $this->view->setContext(
            $context->addGroups(['default', 'developer'])
        );
    }

    /**
     * @SWG\Get(
     *     summary="Get all developers",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @Model(type=App\Entity\Developer::class, groups={"developer"})
     *    ),
     *    @SWG\Response(
     *        response=403,
     *        description="Forbidden",
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
     * @SWG\Tag(name="Developer")
     *
     * @param Request $request
     * @return View
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function __invoke(Request $request): View
    {
        if (!$this->security->isGranted('list', new Developer())) {
            return $this->view->setStatusCode(Response::HTTP_FORBIDDEN);
        }

        $limit = $request->query->get('limit');
        $offset = $request->query->get('offset');

        $response = new ApiListResponse(
            $this->developerService->findAll($limit, $offset),
            $limit,
            $offset,
            $this->developerService->getCount()
        );

        return $this->view->setData($response);
    }
}
