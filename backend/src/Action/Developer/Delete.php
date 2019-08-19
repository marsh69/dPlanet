<?php

namespace App\Action\Developer;

use App\Entity\Developer;
use App\Service\DeveloperService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class Delete
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
     * @SWG\Delete(
     *     summary="Delete a developer",
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
     * )
     * @SWG\Tag(name="Developer")
     *
     * @param Developer $developer
     * @return View
     */
    public function __invoke(Developer $developer): View
    {
        if (!$this->security->isGranted('delete', $developer)) {
            return $this->view->setStatusCode(Response::HTTP_FORBIDDEN);
        };

        $this->developerService->delete($developer);

        return $this->view->setData($developer);
    }
}
