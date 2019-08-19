<?php

namespace App\Action\Developer;

use App\Entity\Developer;
use App\Service\DeveloperService;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class Edit
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
     * @ParamConverter("newDeveloper", class="App\Entity\Developer", converter="fos_rest.request_body")
     * @SWG\Put(
     *     summary="Edit an existing developer",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         type="json",
     *         description="JSON Payload",
     *         format="application/json",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="firstName", type="string", example="John"),
     *             @SWG\Property(property="lastName", type="string", example="Doe"),
     *             @SWG\Property(property="username", type="string", example="TheLegend27"),
     *             @SWG\Property(property="password", type="string", example="TheMyth28"),
     *             @SWG\Property(property="email", type="string", example="thelegend27@gmail.com"),
     *             @SWG\Property(property="profileImage", type="file"),
     *         )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @Model(type=App\Entity\Developer::class, groups={"developer"})
     *    ),
     *    @SWG\Response(
     *         response=403,
     *         description="Forbidden",
     *    ),
     * )
     * @SWG\Tag(name="Developer")
     *
     * @param Developer $developer
     * @param Developer $newDeveloper
     * @param ConstraintViolationListInterface $violationList
     * @return View
     */
    public function __invoke(Developer $developer, Developer $newDeveloper, ConstraintViolationListInterface $violationList): View
    {
        if (!$this->security->isGranted('edit', $developer)) {
            return $this->view->setStatusCode(Response::HTTP_FORBIDDEN);
        };

        if ($violationList->count() > 0) {
            return $this->view
                ->setData($violationList)
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $developer->setFirstName($newDeveloper->getFirstName())
            ->setLastName($newDeveloper->getLastName())
            ->setEmail($newDeveloper->getEmail())
            ->setPlainPassword($newDeveloper->getPlainPassword());

        $this->developerService->save($developer);

        return $this->view->setData($developer);
    }
}
